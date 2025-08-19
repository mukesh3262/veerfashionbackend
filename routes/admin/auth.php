<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\ContentPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubCategoryController;

Route::get('dashboard', DashboardController::class)->name('dashboard');

Route::controller(UserController::class)->group(function () {
    Route::match(['GET', 'POST'], 'users', 'index')->name('users.index');
    Route::get('users/{user}', 'show')->name('users.show');
    Route::get('users/{user}/edit', 'edit')->name('users.edit');
    Route::patch('users/{user}', 'update')->name('users.update');
    Route::delete('users/{user}', 'destroy')->name('users.destroy');
    Route::put('users/status/{user}', 'changeStatus')->name('users.change-status');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('categories/paginated', 'paginatedCategories')->name('categories.paginated');
    Route::match(['GET', 'POST'], 'categories', 'index')->name('categories.index');
    Route::put('categories/status/{category}', 'changeStatus')->name('categories.change-status');
    Route::post('categories/store', 'store')->name('categories.store');
    Route::get('categories/{category}', 'show')->name('categories.show');
    Route::post('categories/{category}', 'update')->name('categories.update');
    Route::delete('categories/{category}', 'destroy')->name('categories.destroy');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('products/paginated', 'paginatedProducts')->name('products.paginated');
    Route::match(['GET', 'POST'], 'products', 'index')->name('products.index');
    Route::put('products/status/{category}', 'changeStatus')->name('products.change-status');
    Route::post('products/store', 'store')->name('products.store');
    Route::get('products/{category}', 'show')->name('products.show');
    Route::post('products/{category}', 'update')->name('products.update');
    Route::delete('products/{category}', 'destroy')->name('products.destroy');
});

Route::prefix('subcategories')->as('subcategories.')->controller(SubCategoryController::class)->group(function () {
    Route::post('/', 'index')->name('index');
    Route::put('status/{subcategory}', 'changeStatus')->name('change-status');
    Route::post('store', 'store')->name('store');
    Route::post('{subcategory}', 'update')->name('update');
    Route::delete('{subcategory}', 'destroy')->name('destroy');
});

Route::controller(ContentPageController::class)->group(function () {
    Route::match(['GET', 'POST'], 'content-pages', 'index')->name('content-pages.index');
    Route::put('content-pages/status/{content_page}', 'changeStatus')->name('content-pages.change-status');
    Route::get('content-pages/create', 'create')->name('content-pages.create');
    Route::post('content-pages/store', 'store')->name('content-pages.store');
    Route::get('content-pages/{content_page}', 'show')->name('content-pages.show');
    Route::get('content-pages/{content_page}/edit', 'edit')->name('content-pages.edit');
    Route::patch('content-pages/{content_page}', 'update')->name('content-pages.update');
    Route::delete('content-pages/{content_page}', 'destroy')->name('content-pages.destroy');
});

Route::controller(PermissionController::class)->group(function () {
    Route::match(['GET', 'POST'], 'permissions', 'index')->name('permissions.index');
    Route::get('permissions/create', 'create')->name('permissions.create');
    Route::post('permissions/store', 'store')->name('permissions.store');
    Route::get('permissions/{permission}/edit', 'edit')->name('permissions.edit');
    Route::patch('permissions/{permission}', 'update')->name('permissions.update');
    Route::delete('permissions/{permission}', 'destroy')->name('permissions.destroy');
});

Route::controller(RoleController::class)->group(function () {
    Route::match(['GET', 'POST'], 'roles', 'index')->name('roles.index');
    Route::get('roles/create', 'create')->name('roles.create');
    Route::post('roles/store', 'store')->name('roles.store');
    Route::get('roles/{role}/edit', 'edit')->name('roles.edit');
    Route::patch('roles/{role}', 'update')->name('roles.update');
    Route::delete('roles/{role}', 'destroy')->name('roles.destroy');
});

Route::controller(AdminController::class)->group(function () {
    Route::match(['GET', 'POST'], 'admins', 'index')->name('admins.index');
    Route::put('admins/status/{admin}', 'changeStatus')->name('admins.change-status');
    Route::get('admins/create', 'create')->name('admins.create');
    Route::post('admins/store', 'store')->name('admins.store');
    Route::get('admins/{admin}', 'show')->name('admins.show');
    Route::get('admins/{admin}/edit', 'edit')->name('admins.edit');
    Route::patch('admins/{admin}', 'update')->name('admins.update');
    Route::delete('admins/{admin}', 'destroy')->name('admins.destroy');
});

Route::controller(SettingController::class)->prefix('setting/')->name('setting.')->group(function () {
    Route::get('mobile-version', 'mobileView')->name('mobile-version');
    Route::patch('mobile/update', 'updateMobileVersionSetting')->name('mobile-version.update');

    Route::get('smtp', 'smtpView')->name('smtp');
    Route::patch('smtp/update', 'updateSmtpSetting')->name('smtp.update');

    Route::match(['get', 'post'], 'seeder', 'seederView')->name('seeder');
    Route::post('seeder/execute', 'executeSeeder')->name('seeder.execute');
});

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

Route::post('email/verification-notification', EmailVerificationNotificationController::class)
    ->middleware('throttle:6,1')
    ->name('verification.send');

Route::controller(ConfirmablePasswordController::class)->group(function () {
    Route::get('confirm-password', 'show')->name('password.confirm');
    Route::post('confirm-password', 'store');
});

Route::put('password', PasswordController::class)->name('password.update');

Route::controller(AuthenticatedSessionController::class)->group(function () {
    Route::post('logout', 'destroy')->name('logout');
});
