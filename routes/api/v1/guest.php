<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\ContentPageController;
use App\Http\Controllers\Api\V1\ForgotPasswordController;
use App\Http\Controllers\Api\V1\InquiryController;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RegisterController;
use App\Http\Controllers\Api\V1\ResetPasswordController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register')->name('register');
});

Route::controller(LoginController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('social-login', 'socialLogin')->name('social-login');
    Route::put('token/refresh', 'refreshToken')->name('token.refresh')->middleware('refresh.sanctum');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::post('password/email', 'sendResetLinkEmail')->name('password.email');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::post('password/reset', 'reset')->name('password.update');
});

Route::controller(SettingController::class)->group(function () {
    Route::post('settings/app-version', 'getAppVersions')->name('settings.app-version');
});

Route::controller(ContentPageController::class)->group(function () {
    Route::get('content-pages/{page_slug}', 'getContent')->name('content-pages.show');
});

Route::controller(InquiryController::class)->group(function () {
    Route::post('inquiry', 'newInquiry')->name('new-inquiry');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('products/filters', 'filters')->name('products.filters');
});
