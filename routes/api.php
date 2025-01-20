<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('checking', function () { 
        return response()->json([
            "message" => "DevBlog API V1 Route",
            "..." => now()
        ]);
    });

    Route::get('posts', [PostController::class, 'index']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        
        // Posts routes - using ID for modifications, URI for display
        Route::post('posts', [PostController::class, 'store']);
        Route::get('posts/by-uri/{uri}', [PostController::class, 'showByUri']);
        Route::get('posts/{id}', [PostController::class, 'show'])->where('id', '[0-9]+');
        Route::put('posts/{id}', [PostController::class, 'update'])->where('id', '[0-9]+');
        Route::delete('posts/{id}', [PostController::class, 'destroy'])->where('id', '[0-9]+');
    });
});
