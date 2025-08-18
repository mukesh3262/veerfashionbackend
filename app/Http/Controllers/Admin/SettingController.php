<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\MobileVersionEnum;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MobileVersionRequest;
use App\Http\Requests\Admin\SeederRequest;
use App\Http\Requests\Admin\SmtpRequest;
use App\Http\Resources\Admin\SeederResource;
use App\Models\AppVersionLog;
use App\Models\Setting;
use App\Pipelines\Admin\Seeder\FilterPipeline;
use App\Pipelines\Admin\Seeder\SortPipeline;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Response as InertiaResponse;
use Throwable;

class SettingController extends Controller
{
    public function mobileView(): InertiaResponse
    {
        $this->authorize('mobile config list');

        $versions = collect(Setting::where('key', MobileVersionEnum::KEY->value)->value('values'));

        $androidVersion = $versions->where('platform', MobileVersionEnum::ANDROID->value)->first();
        $iosVersion = $versions->where('platform', MobileVersionEnum::IOS->value)->first();

        return inertia('Admin/Setting/Mobile', [
            'androidVersion' => $androidVersion,
            'iosVersion' => $iosVersion,
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function updateMobileVersionSetting(MobileVersionRequest $request): RedirectResponse
    {
        $this->authorize('mobile config edit');

        try {
            DB::transaction(function () use ($request) {
                // Prepare updated values
                $updatedValues = [
                    [
                        'platform' => MobileVersionEnum::ANDROID->value,
                        'version' => (int) $request['android']['version'],
                        'force_updateable' => $request['android']['force_updateable'],
                    ],
                    [
                        'platform' => MobileVersionEnum::IOS->value,
                        'version' => (int) $request['ios']['version'],
                        'force_updateable' => $request['ios']['force_updateable'],
                    ],
                ];

                // Update settings
                Setting::where('key', MobileVersionEnum::KEY->value)
                    ->update(['values' => $updatedValues]);

                // Check if any version is greater than 0
                $androidVersion = (int) $request['android']['version'];
                $iosVersion = (int) $request['ios']['version'];

                if ($androidVersion > 0 || $iosVersion > 0) {
                    AppVersionLog::updateOrCreate(
                        [
                            'android_version' => $androidVersion,
                            'ios_version' => $iosVersion,
                        ],
                        [
                            'is_android_force_update' => $request['android']['force_updateable'],
                            'is_ios_force_update' => $request['ios']['force_updateable'],
                        ]
                    );
                }
            });

            return back()->with([
                'success' => [
                    'dialog_type' => 'info', // info | confirm
                    'message' => __('basecode/admin.updated', ['entity' => 'Mobile Version']),
                    'uuid' => Str::uuid(),
                ],
            ]);
        } catch (Throwable $th) {
            return back()->with([
                'error' => $th->getMessage(),
                'uuid' => Str::uuid(),
            ]);
        }
    }

    public function smtpView(): InertiaResponse
    {
        $this->authorize('smtp config list');

        $mailers = collect(array_keys(config('mail.mailers')))
            ->map(fn($name) => ['value' => $name, 'label' => $name]);

        $selected_mailer = [
            'value' => config('mail.default'),
            'label' => config('mail.default'),
        ];

        return inertia('Admin/Setting/Smtp', [
            'mailers' => $mailers,
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            'password' => config('mail.mailers.smtp.password'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
            'selected_mailer' => $selected_mailer,
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function updateSmtpSetting(SmtpRequest $request): RedirectResponse
    {
        $this->authorize('smtp config edit');

        try {
            $envMailKeys = [
                'mailer' => 'MAIL_MAILER',
                'host' => 'MAIL_HOST',
                'port' => 'MAIL_PORT',
                'username' => 'MAIL_USERNAME',
                'password' => 'MAIL_PASSWORD',
                'encryption' => 'MAIL_ENCRYPTION',
                'from_address' => 'MAIL_FROM_ADDRESS',
                'from_name' => 'MAIL_FROM_NAME',
            ];

            foreach ($request->validated() as $key => $value) {
                Artisan::call('env:set', [
                    'key' => $envMailKeys[$key],
                    'value' => $value,
                ]);
            }

            defer(function () {
                Artisan::call('config:cache');
            });

            return back()->with([
                'success' => [
                    'dialog_type' => 'info', // info | confirm
                    'message' => __('basecode/admin.updated', ['entity' => 'SMTP settings']),
                    'uuid' => Str::uuid(),
                ],
            ]);
        } catch (Throwable $th) {
            return back()->with([
                'error' => $th->getMessage(),
                'uuid' => Str::uuid(),
            ]);
        }
    }

    public function seederView(Request $request): InertiaResponse
    {
        $this->authorize('seeder list');

        $files = collect(File::allFiles(base_path('database/seeders')))
            ->map(function ($file, $index) {
                return (object) [
                    'id' => $index + 1,
                    'name' => $file->getFilename(),
                    'updated_at' => Carbon::createFromTimestamp($file->getMTime()),
                    'action' => (object) [
                        'file_path' => $file->getRelativePathname(),
                    ],
                ];
            });

        $files = app(Pipeline::class)
            ->send($files)
            ->through([
                new FilterPipeline($request->filters),
                new SortPipeline($request->sort),
            ])
            ->thenReturn();

        $paginatedFiles = Helper::paginator(
            data: $files,
            currentPage: $request->page ?: 1,
            perPage: $request->offset
        );

        return inertia('Admin/Setting/Seeder', [
            'files' => SeederResource::collection($paginatedFiles),
            'pagination' => $paginatedFiles->toArray(),
            'success' => session('success'),
            'error' => session('error'),
            'uuid' => session('uuid'),
        ]);
    }

    public function executeSeeder(SeederRequest $request): RedirectResponse
    {
        $this->authorize('seeder execute');

        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\' . str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    $request->seeder_path
                ),
            ]);

            return back()->with([
                'success' => [
                    'dialog_type' => 'info', // info | confirm
                    'message' => __('basecode/admin.executed', ['entity' => 'Seeder']),
                    'uuid' => Str::uuid(),
                ],
            ]);
        } catch (Throwable $th) {
            return back()->with([
                'error' => $th->getMessage(),
                'uuid' => Str::uuid(),
            ]);
        }
    }
}
