<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CateringEnquiryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ContactController;

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
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/events', [EventsController::class, 'index'])->name('events');
Route::post('/events/enquiry', [EventsController::class, 'storeEnquiry'])->name('events.enquiry.store');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
/*
|--------------------------------------------------------------------------
| Admin authentication (outside the "admin" auth middleware)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login')->middleware('guest');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.attempt')->middleware('guest');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth');
});

/*
|--------------------------------------------------------------------------
| Admin panel (protected)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('permission:manage menu')->group(function () {
        Route::resource('menu-categories', MenuCategoryController::class)->except('show');
        Route::resource('menu-items', MenuItemController::class)->except('show');
        Route::patch('menu-items/{menuItem}/toggle', [MenuItemController::class, 'toggle'])->name('menu-items.toggle');
    });

    Route::middleware('permission:manage packages')->group(function () {
        Route::resource('packages', PackageController::class)->except('show');
    });

    Route::middleware('permission:manage catering')->group(function () {
        Route::get('catering-enquiries', [CateringEnquiryController::class, 'index'])->name('catering-enquiries.index');
        Route::get('catering-enquiries/{cateringEnquiry}', [CateringEnquiryController::class, 'show'])->name('catering-enquiries.show');
        Route::patch('catering-enquiries/{cateringEnquiry}/status', [CateringEnquiryController::class, 'updateStatus'])->name('catering-enquiries.status');
    });

    Route::middleware('permission:manage settings')->group(function () {
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::middleware('permission:manage users')->group(function () {
        Route::resource('users', UserController::class)->except('show');
    });
});