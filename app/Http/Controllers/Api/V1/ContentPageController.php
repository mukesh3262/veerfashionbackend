<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ContentPageController extends Controller
{
    public function getContent(string $page_slug): HttpResponse|JsonResponse
    {
        $contentPage = ContentPage::query()
            ->where('slug', $page_slug)
            ->first();
        if (! $contentPage) {
            return response([
                'message' => __('basecode/api.not_found', ['entity' => __('label.page')]),
            ], SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response($contentPage->content, SymfonyResponse::HTTP_OK);
    }
}
