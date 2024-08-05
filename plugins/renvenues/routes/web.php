<?php

use App\Http\Middleware\CheckLogin;
use App\Packages\Revenues\src\Controllers\RevenueController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', CheckLogin::class])->group(function () {
    Route::resource('revenues', RevenueController::class);
});
//product
Route::get('api/revenue/product', [RevenueController::class, 'getProductRevenue']);
Route::get('api/revenue/products/daily', [RevenueController::class, 'getProductRevenueByDate']);
Route::get('api/revenue/products/monthly', [RevenueController::class, 'getProductRevenueByMonth']);

// services
Route::get('api/revenue/services', [RevenueController::class, 'getServiceRevenue']);
Route::get('api/revenue/services/daily', [RevenueController::class, 'getServiceRevenueByDate']);
Route::get('api/revenue/services/monthly', [RevenueController::class, 'getServiceRevenueByMonth']);
Route::get('api/revenue/services/weekly-monthly', [RevenueController::class, 'getServiceRevenueByWeekMonth']);
//staff
Route::get('api/revenue/staff-date', [RevenueController::class, 'getRevenueByStaffAndDate']);
