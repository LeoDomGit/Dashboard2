<?php
use Illuminate\Support\Facades\Route;
use Leo\Topics\Controllers\TopicsController;
use App\Http\Middleware\CheckLogin;
Route::middleware(['web', CheckLogin::class])->group(function () {
    Route::resource('topics', TopicsController::class);
    Route::post('edit-slide/{id}',[TopicsController::class,'update']);
});
