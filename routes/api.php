<?php

use App\Http\Controllers\AdminDashboard\AdminNotificationController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Auth\WorkerAuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('auth')->group( function(){
    Route::prefix('admin')->group( function(){

        Route::post('/login', [AdminAuthController::class,'login']);
        Route::post('/register', [AdminAuthController::class,'register']);
        Route::post('/logout', [AdminAuthController::class,'logout']);
        Route::post('/refresh', [AdminAuthController::class,'refresh']);
        Route::post('/user-profile', [AdminAuthController::class,'userProfile']);

    });
    Route::prefix('worker')->group( function(){

        Route::post('/login', [WorkerAuthController::class,'login']);
        Route::post('/register', [WorkerAuthController::class,'register']);
        Route::post('/logout', [WorkerAuthController::class,'logout']);
        Route::post('/refresh', [WorkerAuthController::class,'refresh']);
        Route::post('/user-profile', [WorkerAuthController::class,'userProfile']);

    });
    Route::prefix('client')->group( function(){

        Route::post('/login', [ClientAuthController::class,'login']);
        Route::post('/register', [ClientAuthController::class,'register']);
        Route::post('/logout', [ClientAuthController::class,'logout']);
        Route::post('/refresh', [ClientAuthController::class,'refresh']);
        Route::post('/user-profile', [ClientAuthController::class,'userProfile']);

    });
});
Route::prefix('worker/post')->group( function(){
    Route::post('/add', [PostController::class,'store'])->middleware('auth:worker');
});

Route::prefix('admin/notifications')->middleware('auth:admin')->group( function(){
    Route::get('/all', [AdminNotificationController::class,'index']);
    Route::get('/unread', [AdminNotificationController::class,'unread']);
    Route::post('/markReadAll', [AdminNotificationController::class,'markReadAll']);
    Route::delete('/deleteAll', [AdminNotificationController::class,'deleteAll']);
    Route::delete('/delete/{id}', [AdminNotificationController::class,'delete']);
});