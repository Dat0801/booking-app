<?php

use App\Livewire\Pages\Admin\Bookings;
use App\Livewire\Pages\Admin\Categories;
use App\Livewire\Pages\Admin\Dashboard;
use App\Livewire\Pages\Admin\Login;
use App\Livewire\Pages\Admin\Orders;
use App\Livewire\Pages\Admin\Products;
use App\Livewire\Pages\Admin\Reports;
use App\Livewire\Pages\Admin\Users;
use App\Livewire\Pages\Rooms;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::livewire('/', Login::class)->name('admin.login');

Route::livewire('/admin/login', Login::class);

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::livewire('/admin', Dashboard::class)->name('admin.dashboard');
    Route::livewire('/admin/users', Users::class)->name('admin.users');
    Route::livewire('/admin/categories', Categories::class)->name('admin.categories');
    Route::livewire('/admin/products', Products::class)->name('admin.products');
    Route::livewire('/admin/rooms', Rooms::class)->name('admin.rooms');
    Route::livewire('/admin/orders', Orders::class)->name('admin.orders');
    Route::livewire('/admin/bookings', Bookings::class)->name('admin.bookings');
    Route::livewire('/admin/reports', Reports::class)->name('admin.reports');
});
