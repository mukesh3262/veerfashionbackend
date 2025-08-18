<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\InquiryRequest;
use App\Models\Inquiry;
use App\Traits\Exceptions\ExceptionMessages;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response as FacadesResponse;

class InquiryController extends Controller
{
    use ExceptionMessages;

    public function newInquiry(InquiryRequest $request): JsonResponse
    {
        try {
            Inquiry::create($request->validated());

            return FacadesResponse::success(message: __('basecode/api.request_recorded'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
