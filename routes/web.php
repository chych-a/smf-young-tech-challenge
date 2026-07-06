<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\InvoiceWebController;
use App\Http\Controllers\Web\ProductWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::delete('/products/bulk', [ProductWebController::class, 'bulkDestroy'])->name('web.products.bulk-destroy');
Route::resource('products', ProductWebController::class)
    ->except(['show'])
    ->names('web.products');

Route::delete('/invoices/bulk', [InvoiceWebController::class, 'bulkDestroy'])->name('web.invoices.bulk-destroy');
Route::resource('invoices', InvoiceWebController::class)
    ->except(['store'])
    ->names('web.invoices');
Route::post('/invoices/upload', [InvoiceWebController::class, 'store'])->name('web.invoices.store');
