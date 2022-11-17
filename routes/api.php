<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GrumeController;
use App\Http\Controllers\ContainerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('user')->group(function(){

        Route::get('/', [UserController::class,'getUser']);
        Route::post('/update', [UserController::class,'update']);
    });

    Route::prefix('grumes')->group(
        function () {
            Route::post('/', [GrumeController::class, 'index']);
            Route::get('/{id}', [GrumeController::class, 'show']);
            Route::post('/new',[GrumeController::class,'store']);
            Route::put('/{id}', [GrumeController::class, 'update']);
            Route::post('/delete', [GrumeController::class, 'destroy']);
            Route::post('/upload', [GrumeController::class, 'upload']);
            Route::post('/download', [GrumeController::class, 'download']);
        }
    );
    Route::prefix('containers')->group(
        function () {
            Route::post('/', [ContainerController::class, 'index']);
            Route::get('/{id}', [ContainerController::class, 'show']);
            Route::post('/new',[ContainerController::class,'store']);
            Route::put('/{id}', [ContainerController::class, 'update']);
            Route::post('/delete', [ContainerController::class, 'destroy']);
            Route::post('/upload', [ContainerController::class, 'upload']);
            Route::post('/download', [ContainerController::class, 'download']);
        }
    );
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
