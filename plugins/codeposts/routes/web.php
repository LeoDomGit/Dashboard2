<?php
use Illuminate\Support\Facades\Route;
use Leo\CodePosts\Controllers\CodePostsController;
use App\Http\Middleware\CheckLogin;
Route::middleware(['web', CheckLogin::class])->group(function () {
    Route::resource('codes', CodePostsController::class);
});
