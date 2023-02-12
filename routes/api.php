<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\MoneyRecordingController;
use Illuminate\Support\Facades\Auth;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::put('/user/{id}/update', [AuthController::class, 'update']);
Route::middleware('auth:sanctum')-> post('money-recordings/add', [MoneyRecordingController::class, 'store']);
Route::middleware('auth:sanctum')-> put('/money-recordings/{id}', [MoneyRecordingController::class, 'update']);
Route::middleware('auth:sanctum')->get('/money-recordings/recap', [MoneyRecordingController::class, 'recap']);