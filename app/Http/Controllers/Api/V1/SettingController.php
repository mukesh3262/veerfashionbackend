<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AppVersionRequest;
use App\Services\AppVersionService;
use App\Traits\Exceptions\ExceptionMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Throwable;

class SettingController extends Controller
{
    use ExceptionMessages;

    public function getAppVersions(AppVersionRequest $request): JsonResponse
    {
        try {
            $versionInfo = (new AppVersionService)->getAppVersionInfo($request);

            return FacadesResponse::success(
                message: __('label.ok'),
                data: $versionInfo
            );
        } catch (Throwable $e) {
            return $this->exceptionResponse($e);
        }
    }
}
