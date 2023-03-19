<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityLogController;

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

// Route::get('/get-logs',[ActivityLogController::class, 'index']);

Route::get('/logs', function () {
    return ActivityLogController::all();
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
