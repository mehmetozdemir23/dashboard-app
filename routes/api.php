<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GrumeController;

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
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/grumes',[GrumeController::class,'index']);
    Route::post('/grumes',[GrumeController::class,'index']);
    Route::put('/grumes/{id}',[GrumeController::class,'update']);

    Route::delete('/grumes/{id}',[GrumeController::class,'destroy']);

    Route::post('/grumes/upload',[GrumeController::class,'upload']);
    Route::post('/grumes/download',[GrumeController::class,'download']);

    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
