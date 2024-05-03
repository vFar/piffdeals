<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
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

Route::get('/admin-categories', function () {
    if (Auth::user()->role_id === 2) {
        return Inertia::render('Admin/Categories');
    } else {
        abort(404);
    }

    if (!Auth::check()){
        abort(404);
    }
})->middleware(['auth', 'verified'])->name('admin.categories');

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

Route::get('/admin-users', function () {
    if (Auth::user()->role_id === 2) {
        return Inertia::render('Admin/Users');
    } else {
        abort(404);
    }

    if (!Auth::check()){
        abort(404);
    }
})->middleware(['auth', 'verified'])->name('admin.users');


Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')->middleware('auth');

    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
