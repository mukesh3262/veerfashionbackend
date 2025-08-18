<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Admin;
use App\Models\PersonalAccessToken;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\Sanctum;
use Opcodes\LogViewer\Facades\LogViewer;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Place any application-specific bindings here
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureModels();
        $this->configureVite();
        $this->configureSanctum();
        $this->configurePasswordRules();
        $this->configureRateLimitingForApi();
        $this->configureLogViewerAuthentication();
    }

    /**
     * Configure database commands for production.
     */
    protected function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction(),
        );
    }

    /**
     * Configure Eloquent model settings.
     */
    protected function configureModels(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());
        // Model::shouldBeStrict(! $this->app->isProduction());
    }

    /**
     * Configure Vite settings.
     */
    protected function configureVite(): void
    {
        Vite::prefetch(concurrency: 3);
    }

    /**
     * Configure Sanctum to use a custom personal access token model.
     */
    protected function configureSanctum(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }

    /**
     * Configure default password validation rules.
     */
    protected function configurePasswordRules(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8)
                ->letters()
                ->numbers()
                ->symbols();

            return $this->app->isProduction()
                ? $rule->mixedCase()->uncompromised()
                : $rule;
        });
    }

    /**
     * Configure API rate limiters for the application.
     */
    protected function configureRateLimitingForApi(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $limit = $request->user()
                ? Limit::perMinute(100)->by($request->user()->id)
                : Limit::perMinute(60)->by($request->ip());

            return $limit->response(function (Request $request, array $headers) {
                $retryAfter = $headers['Retry-After'] ?? 60;
                throw new HttpException(
                    statusCode: SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR,
                    message: __('basecode/api.too_many_attempts', ['seconds' => $retryAfter]),
                    headers: $headers
                );
            });
        });
    }

    /**
     * Configure Log Viewer authorization just for admin users.
     */
    protected function configureLogViewerAuthentication(): void
    {
        LogViewer::auth(function ($request) {
            $admin = $request->user('admin');

            return $admin &&
                in_array($admin?->email, [
                    Admin::SUPER_ADMIN_EMAIL,
                    Admin::DEVELOPER_EMAIL,
                ], true);
        });
    }
}
