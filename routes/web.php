<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['guestOrVerified'])->group(function(){
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/store', [ProductController::class, 'index'])->name('store');
    Route::get('/product-detail/{slug}', [ProductController::class, 'show']);

    Route::prefix('/cart')->name('cart.')->group(function(){
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{slug}', [CartController::class, 'add'])->name('add');
        Route::post('/remove/{slug}', [CartController::class, 'remove'])->name('remove');
        Route::post('/update-quantity/{slug}', [CartController::class, 'updateQuantity'])->name('update-quantity');
        Route::post('/get-products', [CartController::class, 'getProducts'])->name('get-products');
    });
});
Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/reset-password', [ProfileController::class, 'resetPassword'])->name('profile.reset-password');
    Route::post('/profile/upprofile.update-passworddate-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
