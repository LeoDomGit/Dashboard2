<?php

use Illuminate\Support\Facades\Route;
use Leo\Bookings\Controllers\BookingController;

Route::apiResource('bookings', BookingController::class);

Route::prefix('api')->group(function () {
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings-customers', [BookingController::class, 'getCustomer']);
    Route::get('/bookings-customers/{id}', [BookingController::class, 'getBillsCustomer']);
    Route::post('/checkOut/{id}', [BookingController::class, 'createBill']);
    Route::get('/bookings-bills', [BookingController::class, 'getBill']);
    Route::get('/bookings-bills/{id}', [BookingController::class, 'successBill']);
    Route::put('/bookings-bills/update/{id}', [BookingController::class, 'updateStatusBill']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    Route::get('/bookings', [BookingController::class, 'api_home'])->middleware('auth:sanctum');
    Route::get('/bookings/nhan-vien', [BookingController::class, 'api_nhan_vien'])->middleware('auth:sanctum');
    Route::get('/submitBooking/{id}', [BookingController::class, 'api_submitbooking_nhan_vien'])->middleware('auth:sanctum');
    Route::get('/cancelBooking/{id}', [BookingController::class, 'api_cancelbooking_nhan_vien'])->middleware('auth:sanctum');
});
