<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\UserAccountController;
use App\Http\Controllers\Api\V1\LoginController;
use Illuminate\Support\Facades\Route;

Route::controller(UserAccountController::class)->group(function () {
    Route::get('profile', 'getUserProfile')->name('user.profile.show');
    Route::post('profile', 'updateUserProfile')->name('user.profile.update');
    // Route::patch('update-password', 'updatePassword')->name('update-password');
    // Route::patch('update-locale', 'updateLocale')->name('update-locale');
    Route::patch('accept-push', 'acceptPush')->name('accept-push');
    Route::patch('send-otp', 'sendOTP')->name('send-otp');
    Route::patch('verify-otp', 'verifyOTP')->name('verify-otp');
    Route::delete('profile/delete', 'deleteUserAccount')->name('user.delete');
});

Route::controller(LoginController::class)->group(function () {
    Route::delete('logout', 'logout')->name('logout');
    Route::delete('past/logout', 'logoutFromPastLogin')->name('past-logout');
});

Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::controller(CategoryController::class)->group(function () {
    Route::get('categories', 'categories')->name('categories');
    Route::get('category/{category}/sub-categories', 'subCategories')->name('category.sub-categories');
});