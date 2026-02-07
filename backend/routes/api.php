<?php

use App\Http\Controllers\Api\V1\Admin\AdminBookingController;
use App\Http\Controllers\Api\V1\Admin\AdminCategoryController;
use App\Http\Controllers\Api\V1\Admin\AdminCouponController;
use App\Http\Controllers\Api\V1\Admin\AdminDashboardController;
use App\Http\Controllers\Api\V1\Admin\AdminOrderController;
use App\Http\Controllers\Api\V1\Admin\AdminProductController;
use App\Http\Controllers\Api\V1\Admin\AdminReviewController;
use App\Http\Controllers\Api\V1\Admin\AdminUserController;
use App\Http\Controllers\Api\V1\Admin\ImageController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Customer\BookingController;
use App\Http\Controllers\Api\V1\Customer\CartController;
use App\Http\Controllers\Api\V1\Customer\CouponController;
use App\Http\Controllers\Api\V1\Customer\OrderController;
use App\Http\Controllers\Api\V1\Customer\ProductController;
use App\Http\Controllers\Api\V1\Customer\ReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);

    Route::get('categories', [ProductController::class, 'categories']);
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/email/verify', [AuthController::class, 'verifyEmail'])->name('verification.verify');
        Route::post('auth/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])->name('verification.send');

        Route::get('cart', [CartController::class, 'show']);
        Route::post('cart/items', [CartController::class, 'store']);
        Route::put('cart/items/{id}', [CartController::class, 'update']);
        Route::delete('cart/items/{id}', [CartController::class, 'destroy']);
        Route::delete('cart', [CartController::class, 'clear']);

        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{id}', [OrderController::class, 'show']);
        Route::post('orders', [OrderController::class, 'store']);
        Route::post('orders/{id}/cancel', [OrderController::class, 'cancel']);

        Route::get('bookings', [BookingController::class, 'index']);
        Route::get('bookings/{id}', [BookingController::class, 'show']);
        Route::post('bookings', [BookingController::class, 'store']);
        Route::post('bookings/{id}/cancel', [BookingController::class, 'cancel']);

        Route::get('reviews', [ReviewController::class, 'index']);
        Route::get('reviews/{id}', [ReviewController::class, 'show']);
        Route::post('reviews', [ReviewController::class, 'store']);
        Route::put('reviews/{id}', [ReviewController::class, 'update']);
        Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);

        Route::post('coupons/validate', [CouponController::class, 'validateCoupon']);

        Route::middleware('role:admin')->prefix('admin')->group(function () {
            Route::get('dashboard/summary', [AdminDashboardController::class, 'summary']);
            Route::get('dashboard/revenue', [AdminDashboardController::class, 'revenue']);
            Route::get('dashboard/products', [AdminDashboardController::class, 'products']);
            Route::get('dashboard/customers', [AdminDashboardController::class, 'customers']);

            Route::get('reports/sales', [ReportController::class, 'sales']);
            Route::get('reports/products', [ReportController::class, 'products']);
            Route::get('reports/customers', [ReportController::class, 'customers']);

            Route::apiResource('categories', AdminCategoryController::class)->except(['create', 'edit']);
            Route::apiResource('products', AdminProductController::class)->except(['create', 'edit']);
            Route::get('orders', [AdminOrderController::class, 'index']);
            Route::get('orders/{id}', [AdminOrderController::class, 'show']);
            Route::patch('orders/{id}/status', [AdminOrderController::class, 'updateStatus']);

            Route::get('bookings', [AdminBookingController::class, 'index']);
            Route::get('bookings/{id}', [AdminBookingController::class, 'show']);
            Route::patch('bookings/{id}/status', [AdminBookingController::class, 'updateStatus']);

            Route::get('users', [AdminUserController::class, 'index']);
            Route::get('users/{id}', [AdminUserController::class, 'show']);
            Route::patch('users/{id}', [AdminUserController::class, 'update']);
            Route::patch('users/{id}/roles', [AdminUserController::class, 'updateRoles']);
            Route::delete('users/{id}', [AdminUserController::class, 'destroy']);
            Route::patch('users/{id}/restore', [AdminUserController::class, 'restore']);

            Route::get('reviews', [AdminReviewController::class, 'index']);
            Route::get('reviews/{id}', [AdminReviewController::class, 'show']);
            Route::patch('reviews/{id}/status', [AdminReviewController::class, 'updateStatus']);
            Route::delete('reviews/{id}', [AdminReviewController::class, 'destroy']);

            Route::post('images/upload', [ImageController::class, 'upload']);
            Route::delete('images', [ImageController::class, 'delete']);

            Route::apiResource('coupons', AdminCouponController::class)->except(['create', 'edit']);
        });
    });
});
