<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NavigationDataController;
use App\Http\Controllers\Admin\ClientAccountController;
use App\Http\Controllers\Admin\CategoryGoodsController;
use App\Http\Controllers\Admin\GroupGoodsController;
use App\Http\Controllers\Admin\AttributesGoodsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', function () {
    return redirect()->intended('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
});

Route::get('/cookies-information', function () {
    return Inertia::render('CookiesInfo');
});

Route::get('/terms-of-use', function () {
    return Inertia::render('TermsOfUse');
});

Route::get('/contact', function () {
    return Inertia::render('Contact');
});

Route::get('/profile', function () {
    return Inertia::render('Profile');
});

// Route::post('/contact', 'ContactController@sendMail')->name('contact.send');
Route::post('/contact', [ContactController::class, 'sendMail'])->name('contact.send');

Route::get('/about-us', function () {
    return Inertia::render('AboutUs');
});

Route::get('/admin-dashboard', function () {
    if (Auth::user()->role_id === 2) {
        return Inertia::render('Admin/Statistics');
    } else {
        abort(404);
    }

    if (!Auth::check()){
        abort(404);
    }
})->middleware(['auth', 'verified'])->name('admin.dashboard');

Route::get('/admin-orders', function () {
    if (Auth::user()->role_id === 2) {
        return Inertia::render('Admin/Orders');
    } else {
        abort(404);
    }

    if (!Auth::check()){
        abort(404);
    }
})->middleware(['auth', 'verified'])->name('admin.orders');

Route::get('/admin-goods', function () {
    if (Auth::user()->role_id === 2) {
        return Inertia::render('Admin/Goods');
    } else {
        abort(404);
    }

    if (!Auth::check()){
        abort(404);
    }
})->middleware(['auth', 'verified'])->name('admin.goods');


Route::get('/admin-logfiles', function () {
    if (Auth::user()->role_id === 2) {
        return Inertia::render('Admin/Logfiles');
    } else {
        abort(404);
    }

    if (!Auth::check()){
        abort(404);
    }
})->middleware(['auth', 'verified'])->name('admin.logfiles');

Route::get('/admin-administrators', function () {
    if (Auth::user()->role_id === 2) {
        return Inertia::render('Admin/Administrators');
    } else {
        abort(404);
    }

    if (!Auth::check()){
        abort(404);
    }
})->middleware(['auth', 'verified'])->name('admin.administrators');

Route::get('/navigation-data', [NavigationDataController::class, 'getActiveCategories'])->name('navigation.data');

Route::prefix('admin-attributes')->middleware(['auth', 'can:admin-access'])->group(function () {
    // Route::get('/', [AttributesGoodsController::class, 'index'])->name('admin.groups.index');
    // Route::post('/store', [AttributesGoodsController::class, 'store'])->name('admin.groups.store');
    // Route::patch('/admin-groups/{id}', [AttributesGoodsController::class, 'update'])->name('admin.groups.update');
});

Route::prefix('admin-groups')->middleware(['auth', 'can:admin-access'])->group(function () {
    Route::get('/', [GroupGoodsController::class, 'index'])->name('admin.groups.index');
    Route::post('/store', [GroupGoodsController::class, 'store'])->name('admin.groups.store');
    Route::patch('/admin-groups/{id}', [GroupGoodsController::class, 'update'])->name('admin.groups.update');
});


Route::prefix('admin-categories')->middleware(['auth', 'can:admin-access'])->group(function () {
    Route::get('/', [CategoryGoodsController::class, 'index'])->name('admin.categories.list');
    Route::post('/store', [CategoryGoodsController::class, 'store'])->name('admin.goods.storeCategory');
    Route::patch('/admin-categories/{id}', [CategoryGoodsController::class, 'update'])->name('admin.goods.updateCategory');
});

Route::prefix('admin-users')->name('admin-users.')->group(function () {
    Route::get('/', [ClientAccountController::class, 'index'])->name('index')->middleware('can:admin-access');
    Route::post('/store', [ClientAccountController::class, 'storeAdmin'])->name('storeAdmin')->middleware('can:admin-access');
    Route::get('/{id}', [ClientAccountController::class, 'show'])->name('show')->middleware('can:admin-access');
    Route::post('/password/email', [ClientAccountController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('can:admin-access');
    Route::patch('/update-status', [ClientAccountController::class, 'updateStatus'])->name('updateStatus')->middleware('can:admin-access');
    Route::delete('/delete', [ClientAccountController::class, 'deleteUsers'])->name('delete')->middleware('can:admin-access');
});



Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')->middleware('auth');

    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
