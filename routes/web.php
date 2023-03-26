<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
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
        Route::get('/remove/{slug}', [CartController::class, 'remove'])->name('remove');
        Route::get('/update-quantity/{slug}', [CartController::class, 'updateQuantity'])->name('update-quantity');
    });
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
