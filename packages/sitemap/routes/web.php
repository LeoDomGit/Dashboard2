<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckLogin;
use Leo\Sitemap\Controllers\SitemapController;

Route::middleware(['web', CheckLogin::class])->group(function () {
    Route::resource('sitemap', SitemapController::class);
});
Route::get('/api/sitemap', [SitemapController::class,'api_index']);
Route::get('/api/sitemap/{id}', [SitemapController::class,'api_single']);


