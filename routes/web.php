<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GoodsPreviewController;
use App\Http\Controllers\NavigationDataController;
use App\Http\Controllers\FilterGoodsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

use App\Http\Controllers\Admin\ClientAccountController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryGoodsController;
use App\Http\Controllers\Admin\GroupGoodsController;
use App\Http\Controllers\Admin\AttributesGoodsController;
use App\Http\Controllers\Admin\GoodsController;

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\AddressOrderController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('welcome');

Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
});
Route::get('/cookies-information', function () {
    return Inertia::render('CookiesInfo');
});
Route::get('/terms-of-use', function () {
    return Inertia::render('TermsOfUse');
});

Route::get('/contact', [ContactController::class, 'create']);
Route::post('/contact', [ContactController::class, 'sendMail'])->name('contact.send');

Route::get('/about-us', function () {
    return Inertia::render('AboutUs');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/remove', [ProfileController::class, 'remove'])->name('profile.remove');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/orders', [ProfileController::class, 'ordersIndex'])->name('profile.orders')->middleware('preventAdminAccess');
    Route::get('/profile/orders/{orderId}', [ProfileController::class, 'orderDetail'])->name('profile.orderDetail');
});

Route::prefix('cart')->middleware(['auth', 'verified', 'preventAdminAccess'])->group(function () {
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/', [CartController::class, 'index'])->name('cart.index'); 
    Route::get('/items', [CartController::class, 'getCartItems'])->name('cart.items');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update-and-checkout', [CartController::class, 'updateAndCheckout'])->name('cart.updateAndCheckout');
});

//'cart.not.empty' => \App\Http\Middleware\EnsureCartIsNotEmpty::class,
Route::prefix('checkout')->middleware(['auth', 'verified', 'cart.not.empty'])->group(function () {
    Route::get('/', [AddressOrderController::class, 'create'])->name('checkout.index');
    Route::post('/store', [AddressOrderController::class, 'store'])->name('checkout.store');
});


Route::prefix('admin-orders')->middleware(['auth', 'checkAdminRole'])->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('admin-orders.index');
    Route::get('/{order}', [OrderController::class, 'show'])->name('admin-orders.show');
    Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('admin-orders.update');
});

Route::prefix('admin')->middleware(['auth', 'checkAdminRole'])->group(function () {
    // General admin dashboard, stats, etc.
    // Recent orders accessible from any admin page
    Route::get('/order-notifications', [OrderController::class, 'orderNotifications'])->name('admin.order-notifications');


    // Other admin routes...
});


Route::prefix('navigation-data')->group(function () {
    Route::get('/categories', [NavigationDataController::class, 'getActiveCategories'])->name('navigation.data.categories');
    Route::get('/goods-count', [NavigationDataController::class, 'getActiveGoodsCount'])->name('navigation.data.goodsCount');
    Route::get('/active-goods', [NavigationDataController::class, 'getActiveGoods'])->name('navigation.data.activeGoods');
    Route::get('/search-goods', [NavigationDataController::class, 'searchGoods'])->name('navigation.data.searchGoods');
});


Route::get('/goods/{id}', [GoodsPreviewController::class, 'show'])->name('goods.show');
Route::get('/goods', [FilterGoodsController::class, 'index'])->name('goods.filter');

Route::prefix('admin-goods')->middleware(['auth', 'checkAdminRole'])->group(function () {
    Route::get('/', [GoodsController::class, 'index'])->name('admin.goods.index');
    Route::post('/store', [GoodsController::class, 'store'])->name('admin.goods.store');
    Route::get('/{id}/edit', [GoodsController::class, 'edit'])->name('admin.goods.edit');
    Route::patch('/{id}', [GoodsController::class, 'update'])->name('admin.goods.update');
    Route::get('/{id}', [GoodsController::class, 'show'])->name('admin.goods.show');
    Route::post('/upload-image', [GoodsController::class, 'uploadImage'])->name('admin.goods.upload-image');
    Route::delete('/{id}', [GoodsController::class, 'destroy'])->name('admin.goods.destroy');
});

Route::prefix('admin-attributes')->middleware(['auth', 'checkAdminRole'])->group(function () {
    Route::get('/', [AttributesGoodsController::class, 'index'])->name('admin.attributes.index');
    Route::post('/store', [AttributesGoodsController::class, 'store'])->name('admin.attributes.store');
    Route::patch('/admin-attributes/{id}', [AttributesGoodsController::class, 'update'])->name('admin.attributes.update');
    Route::delete('/{id}', [AttributesGoodsController::class, 'destroy'])->name('admin.attributes.destroy');
});

Route::prefix('admin-groups')->middleware(['auth', 'checkAdminRole'])->group(function () {
    Route::get('/', [GroupGoodsController::class, 'index'])->name('admin.groups.index');
    Route::post('/store', [GroupGoodsController::class, 'store'])->name('admin.groups.store');
    Route::patch('/admin-groups/{id}', [GroupGoodsController::class, 'update'])->name('admin.groups.update');
    Route::delete('/{id}', [GroupGoodsController::class, 'destroy'])->name('admin.groups.destroy');
});

Route::prefix('admin-categories')->middleware(['auth', 'checkAdminRole'])->group(function () {
    Route::get('/', [CategoryGoodsController::class, 'index'])->name('admin.categories.list');
    Route::post('/store', [CategoryGoodsController::class, 'store'])->name('admin.goods.storeCategory');
    Route::patch('/admin-categories/{id}', [CategoryGoodsController::class, 'update'])->name('admin.goods.updateCategory');
    Route::delete('/admin-categories/{id}', [CategoryGoodsController::class, 'destroy'])->name('admin.goods.destroyCategory');
});

Route::prefix('admin-users')->name('admin-users.')->group(function () {
    Route::get('/', [ClientAccountController::class, 'index'])->name('index')->middleware('checkAdminRole');
    Route::post('/store', [ClientAccountController::class, 'storeAdmin'])->name('storeAdmin')->middleware('checkAdminRole');
    Route::get('/{id}', [ClientAccountController::class, 'show'])->name('show')->middleware('checkAdminRole');
    Route::post('/password/email', [ClientAccountController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('checkAdminRole');
    Route::patch('/update-status', [ClientAccountController::class, 'updateStatus'])->name('updateStatus')->middleware('checkAdminRole');
    Route::delete('/delete', [ClientAccountController::class, 'deleteUsers'])->name('delete')->middleware('checkAdminRole');
});

require __DIR__.'/auth.php';
