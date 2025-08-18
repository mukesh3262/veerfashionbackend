<?php

declare(strict_types=1);

use App\Http\Controllers\Frontend\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Frontend\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Frontend\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Frontend\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Frontend\Auth\PasswordController;
use App\Http\Controllers\Frontend\Auth\VerifyEmailController;
use App\Http\Controllers\Frontend\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', function () {
    return inertia('Frontend/Dashboard');
})->middleware('verified')->name('dashboard');

Route::controller(ProfileController::class)->group(function () {
    Route::get('profile', 'edit')->name('profile.edit');
    Route::patch('profile', 'update')->name('profile.update');
    Route::delete('profile', 'destroy')->name('profile.destroy');
});

Route::get('verify-email', EmailVerificationPromptController::class)
    ->name('verification.notice');

Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::controller(EmailVerificationNotificationController::class)->group(function () {
    Route::post('email/verification-notification', 'store')
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::controller(ConfirmablePasswordController::class)->group(function () {
    Route::get('confirm-password', 'show')->name('password.confirm');
    Route::post('confirm-password', 'store');
});

Route::put('password', PasswordController::class)->name('password.update');

Route::controller(AuthenticatedSessionController::class)->group(function () {
    Route::post('logout', 'destroy')->name('logout');
});
