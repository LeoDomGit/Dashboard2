<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckLogin;
use Leo\Services\Controllers\ServicesController;

Route::middleware(['web', CheckLogin::class])->group(function () {
    Route::resource('services', ServicesController::class);
    Route::post('/update-services/{id}',[ServicesController::class,'update']);
});

Route::prefix('api/services/')->group(function () {
    Route::get('/', [ServicesController::class,'api_service']);
    Route::get('home', [ServicesController::class,'api_home']);
    Route::get('service/{id}', [ServicesController::class,'api_single']);

});
