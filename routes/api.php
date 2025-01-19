<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {   
    
    Route::apiResource('posts', PostController::class);
});
