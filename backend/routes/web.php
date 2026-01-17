<?php

use App\Livewire\Pages\Admin\Dashboard;
use App\Livewire\Pages\Admin\Login;
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
});
