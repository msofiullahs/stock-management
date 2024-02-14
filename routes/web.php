<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::get('category/ajax', [CategoryController::class, 'ajaxCategories'])->name('catAjax');
    Route::resource('category', CategoryController::class)->except(['create', 'show', 'edit']);

    Route::get('product/generate-code', [ProductController::class, 'codeGenerate'])->name('codeGenerate');
    Route::get('product/check-code', [ProductController::class, 'codeCheck'])->name('codeCheck');
    Route::get('product/ajax', [ProductController::class, 'ajaxProducts'])->name('productAjax');
    Route::resource('product', ProductController::class)->except(['create', 'show']);

    Route::get('price/ajax', [PriceController::class, 'ajaxPrices'])->name('priceAjax');
    Route::resource('price', PriceController::class);

    Route::get('supplier/generate-code', [SupplierController::class, 'supplierGenerate'])->name('supplierGenerate');
    Route::get('supplier/check-code', [SupplierController::class, 'supplierCheck'])->name('supplierCheck');
    Route::get('supplier/ajax', [SupplierController::class, 'ajaxSuppliers'])->name('supplierAjax');
    Route::resource('supplier', SupplierController::class)->except(['create', 'show']);

    Route::resource('stock', StockController::class);

    Route::resource('user', UserController::class);
});
