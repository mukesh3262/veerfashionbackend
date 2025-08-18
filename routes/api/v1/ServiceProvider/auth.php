<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\ServiceProvider\UserAccountController;
use App\Http\Controllers\Api\V1\ServiceProvider\LoginController;
use Illuminate\Support\Facades\Route;

Route::controller(UserAccountController::class)->group(function () {
    Route::get('user/profile', 'getUserProfile')->name('user.profile.show');
    Route::patch('user/profile', 'updateUserProfile')->name('user.profile.update');
    Route::patch('update-password', 'updatePassword')->name('update-password');
    Route::patch('update-locale', 'updateLocale')->name('update-locale');
    Route::patch('accept-push', 'acceptPush')->name('accept-push');
    Route::patch('send-otp', 'sendOTP')->name('send-otp');
    Route::patch('verify-otp', 'verifyOTP')->name('verify-otp');
});

Route::controller(LoginController::class)->group(function () {
    Route::delete('logout', 'logout')->name('logout');
    Route::delete('past/logout', 'logoutFromPastLogin')->name('past-logout');
});
