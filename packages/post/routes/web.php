<?php

use Illuminate\Support\Facades\Route;
use Leo\Post\Controllers\PostCateController;
use Leo\Post\Controllers\PostController;
use App\Http\Middleware\CheckLogin;

Route::middleware(['web', CheckLogin::class])->group(function () {
    Route::resource('post-collections', PostCateController::class);
    Route::resource('posts', PostController::class);
    Route::post('posts/{id}', [PostController::class,'getProductsList']);
    Route::post('update-post/{id}', [PostController::class,'update']);

});

Route::prefix('api')->group(function () {
    Route::get('/post-collections',[PostCateController::class,'api_index']);
    Route::get('/post-collections/{id}',[PostCateController::class,'api_show']);
    Route::get('/posts',[PostController::class,'api_get']);
    Route::get('/highlight-posts',[PostController::class,'api_highlight']);
    Route::get('/posts/{id}',[PostController::class,'api_single']);

});
