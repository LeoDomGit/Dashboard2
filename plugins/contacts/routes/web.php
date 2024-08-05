<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckLogin;
use Leo\Contacts\Controllers\ContactsController;



Route::middleware(['web',CheckLogin::class])->group(function () {
    Route::resource('contacts', ContactsController::class);
});

Route::prefix('api')->group(function () {
    Route::prefix('contacts')->group(function () {
        Route::post('/',[ContactsController::class,'store']);
    });

});
