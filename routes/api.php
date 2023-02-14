<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/add_topic',[\App\Http\Controllers\HelpTopicController::class,'create']);
Route::get('/get_topic',[\App\Http\Controllers\HelpTopicController::class,'index']);
Route::get('/get_topic/{helpTopic}',[\App\Http\Controllers\HelpTopicController::class,'show']);
Route::patch('/get_topic/{helpTopic}',[\App\Http\Controllers\HelpTopicController::class,'update']);
Route::delete('/get_topic/{helpTopic}',[\App\Http\Controllers\HelpTopicController::class,'delete']);
