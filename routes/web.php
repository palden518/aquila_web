<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AddonController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CateringEnquiryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\OrdermenuController;
use App\Http\Controllers\SpecialsController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/ordermenu', [OrdermenuController::class, 'index'])->name('ordermenu');
Route::get('/specials', [SpecialsController::class, 'index'])->name('specials');
Route::get('/events', [EventsController::class, 'index'])->name('events');
Route::post('/events/enquiry', [EventsController::class, 'storeEnquiry'])->name('events.enquiry.store');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Online ordering: cart -> checkout -> confirmation
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware('throttle:60,1');
Route::patch('/cart/{menuItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{menuItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:10,1');
Route::get('/order/{order:order_number}', [OrderController::class, 'show'])->name('orders.confirmation')->middleware('throttle:30,1');

// Stripe card payment
Route::get('/order/{order:order_number}/payment/return', [PaymentController::class, 'return'])->name('orders.payment.return')->middleware('throttle:30,1');
Route::post('/order/{order:order_number}/pay', [PaymentController::class, 'retry'])->name('orders.pay')->middleware('throttle:10,1');

// Called by Stripe's servers, so it has no session/CSRF token. Security comes from
// verifying Stripe's signature inside the controller. The three class names cover
// the CSRF middleware's name across Laravel versions.
Route::post('/stripe/webhook', StripeWebhookController::class)
    ->name('stripe.webhook')
    ->withoutMiddleware([
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ]);

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
        Route::resource('addons', AddonController::class)->except('show');
        Route::patch('menu-items/{menuItem}/toggle', [MenuItemController::class, 'toggle'])->name('menu-items.toggle');
    });

    Route::middleware('permission:manage packages')->group(function () {
        Route::resource('packages', PackageController::class)->except('show');
    });

    Route::middleware('permission:manage catering')->group(function () {
        Route::get('catering-enquiries', [CateringEnquiryController::class, 'index'])->name('catering-enquiries.index');
        Route::get('catering-enquiries/{cateringEnquiry}', [CateringEnquiryController::class, 'show'])->name('catering-enquiries.show');
        Route::patch('catering-enquiries/{cateringEnquiry}/status', [CateringEnquiryController::class, 'updateStatus'])->name('catering-enquiries.status');

        Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
        Route::patch('contact-messages/{contactMessage}/status', [ContactMessageController::class, 'updateStatus'])->name('contact-messages.status');

        Route::get('reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::get('reservations/{reservation}', [AdminReservationController::class, 'show'])->name('reservations.show');
        Route::patch('reservations/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.status');
    });

    Route::middleware('permission:manage orders')->group(function () {
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    });

    Route::middleware('permission:manage settings')->group(function () {
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::middleware('permission:manage users')->group(function () {
        Route::resource('users', UserController::class)->except('show');
    });
});
