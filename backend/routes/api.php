<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Customer\ProductController;
use App\Http\Controllers\Api\V1\Customer\CartController;
use App\Http\Controllers\Api\V1\Customer\OrderController;
use App\Http\Controllers\Api\V1\Customer\BookingController;
use App\Http\Controllers\Api\V1\Admin\AdminProductController;
use App\Http\Controllers\Api\V1\Admin\AdminOrderController;
use App\Http\Controllers\Api\V1\Admin\AdminBookingController;
use App\Http\Controllers\Api\V1\Admin\AdminCategoryController;
use App\Http\Controllers\Api\V1\Admin\AdminUserController;
use App\Http\Controllers\Api\V1\Admin\AdminDashboardController;

Route::prefix('v1')->group(function () {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('auth/me', [AuthController::class, 'me']);
            Route::post('auth/logout', [AuthController::class, 'logout']);

            Route::get('categories', [ProductController::class, 'categories']);
            Route::get('products', [ProductController::class, 'index']);
            Route::get('products/{id}', [ProductController::class, 'show']);

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

            Route::middleware('role:admin')->prefix('admin')->group(function () {
                Route::get('dashboard/summary', [AdminDashboardController::class, 'summary']);

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
            });
        });
});
