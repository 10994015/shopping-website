<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
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
    Route::get('/store/{category}', [ProductController::class, 'category'])->name('store.category');
    Route::get('/product-detail/{slug}', [ProductController::class, 'show']);

    Route::get('/change-store-sort', [ProductController::class, 'sort']);
    Route::get('/search-store', [ProductController::class, 'search']);
    Route::get('/filter-store-price', [ProductController::class, 'filter']);

    Route::prefix('/cart')->name('cart.')->group(function(){
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{slug}', [CartController::class, 'add'])->name('add');
        Route::post('/remove/{slug}', [CartController::class, 'remove'])->name('remove');
        Route::post('/update-quantity/{slug}', [CartController::class, 'updateQuantity'])->name('update-quantity');
        Route::post('/get-products', [CartController::class, 'getProducts'])->name('get-products');
    });
    
    Route::post("/discount", [DiscountController::class, 'input']);
    Route::post("/add-favorite", [FavoriteController::class, 'add']);
    Route::post("/remove-favorite", [FavoriteController::class, 'remove']);
    Route::get('/favorites',[FavoriteController::class, 'index']);
});
Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/reset-password', [ProfileController::class, 'resetPassword'])->name('profile.reset-password');
    Route::post('/profile/upprofile.update-passworddate-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.checkout');
    Route::post('/checkout/{order}', [CheckoutController::class, 'checkoutOrder'])->name('checkout.checkout-order');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/callback', [CheckoutController::class, 'callback'])->name('checkout.callback');
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/{order}', [OrderController::class, 'view'])->name('order.view');
    Route::post('/comment', [ProductController::class, 'createComment'])->name('comment.create');
    Route::delete('/comment/{id}', [ProductController::class, 'deleteComment'])->name('comment.delete');
});
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
