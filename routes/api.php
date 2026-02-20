<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FreezeController;
use App\Http\Controllers\HistoryStockController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionDetailController;
use App\Http\Middleware\TokenAuth;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/admin/login', [AdminController::class, 'login'])->middleware(TokenAuth::class);
Route::post('/admin/logout', [AdminController::class, 'logout'])->middleware(TokenAuth::class);

Route::post('/product/all/profit/credit', [ProductController::class, 'totalALlProfitCredit'])->middleware(TokenAuth::class);
Route::post('/product/total', [ProductController::class, 'total'])->middleware(TokenAuth::class);
Route::post('/product/edit', [ProductController::class, 'edit'])->middleware(TokenAuth::class);
Route::post('/product/load', [ProductController::class, 'load'])->middleware(TokenAuth::class);
Route::post('/product/save', [ProductController::class, 'save'])->middleware(TokenAuth::class);
Route::post('/product/delete', [ProductController::class, 'delete'])->middleware(TokenAuth::class);
Route::post('/product/search', [ProductController::class, 'search'])->middleware(TokenAuth::class);
Route::post('/product/top', [ProductController::class, 'top'])->middleware(TokenAuth::class);
Route::post('/product/all/profit', [ProductController::class, 'allProfit'])->middleware(TokenAuth::class);

Route::post('/product/load/barcode', [ProductController::class, 'loadBarcode'])->middleware(TokenAuth::class);

Route::post('/satuan/save', [SatuanController::class, 'save'])->middleware(TokenAuth::class);
Route::post('/satuan/load', [SatuanController::class, 'load'])->middleware(TokenAuth::class);
Route::post('/satuan/delete', [SatuanController::class, 'delete'])->middleware(TokenAuth::class);

Route::post('/ppn/save', [SettingController::class, 'savePPN'])->middleware(TokenAuth::class);
Route::post('/ppn/load', [SettingController::class, 'loadPPN'])->middleware(TokenAuth::class);

Route::post('/category/save', [CategoryController::class, 'save'])->middleware(TokenAuth::class);
Route::post('/category/load', [CategoryController::class, 'load'])->middleware(TokenAuth::class);
Route::post('/category/delete', [CategoryController::class, 'delete'])->middleware(TokenAuth::class);

Route::post('/transaction/search', [TransactionController::class, 'search'])->middleware(TokenAuth::class);
Route::post('/transaction/save', [TransactionController::class, 'save'])->middleware(TokenAuth::class);
Route::post('/transaction/load', [TransactionController::class, 'load'])->middleware(TokenAuth::class);
Route::post('/transaction/load/graphic/cash/credit', [TransactionController::class, 'loadGraphicCashCredit'])->middleware(TokenAuth::class);
Route::post('/transaction/delete', [TransactionController::class, 'delete'])->middleware(TokenAuth::class);
Route::post('/transaction/total', [TransactionController::class, 'total'])->middleware(TokenAuth::class);

Route::post('/transaction/total/today', [TransactionController::class, 'totalToday'])->middleware(TokenAuth::class);
Route::post('/transaction/total/today/credit', [TransactionController::class, 'totalTodayCredit'])->middleware(TokenAuth::class);
Route::post('/transaction/total/month', [TransactionController::class, 'totalMonth'])->middleware(TokenAuth::class);
Route::post('/transaction/total/month/credit', [TransactionController::class, 'totalMonthCredit'])->middleware(TokenAuth::class);
Route::post('/transaction/details/load', [TransactionDetailController::class, 'load'])->middleware(TokenAuth::class);

Route::post('/profile/load', [ProfileController::class, 'load'])->middleware(TokenAuth::class);
Route::post('/profile/update', [ProfileController::class, 'update'])->middleware(TokenAuth::class);
Route::post('/profile/change/password', [ProfileController::class, 'changePassword'])->middleware(TokenAuth::class);

Route::post('/customer/load', [CustomerController::class, 'load'])->middleware(TokenAuth::class);
Route::post('/customer/all', [CustomerController::class, 'load'])->middleware(TokenAuth::class);
Route::post('/customer/update', [CustomerController::class, 'update'])->middleware(TokenAuth::class);
Route::post('/customer/save', [CustomerController::class, 'save'])->middleware(TokenAuth::class);
Route::post('/customer/delete', [CustomerController::class, 'delete'])->middleware(TokenAuth::class);
Route::post('/customer/search', [CustomerController::class, 'search'])->middleware(TokenAuth::class);
Route::post('/customer/pay/credit', [CustomerController::class, 'payCredit'])->middleware(TokenAuth::class);
Route::post('/customer/payment/history', [CustomerController::class, 'paymentHistory'])->middleware(TokenAuth::class);

Route::post('/history/stock/out/load', [HistoryStockController::class, 'load'])->middleware(TokenAuth::class);
Route::post('/history/stock/out/search', [HistoryStockController::class, 'search'])->middleware(TokenAuth::class);

Route::post('/freeze/check', [FreezeController::class, 'index'])->middleware(TokenAuth::class);