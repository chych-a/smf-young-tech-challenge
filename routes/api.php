<?php

use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\InvoiceUploadController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => ['status' => 'ok']);

Route::apiResource('products', ProductController::class);
Route::apiResource('invoices', InvoiceController::class)->except(['store']);
Route::post('/invoices/upload', InvoiceUploadController::class)->name('invoices.upload');
