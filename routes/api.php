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

Route::post('/branch',[\App\Http\Controllers\BranchController::class,'create']);
Route::get('/branch',[\App\Http\Controllers\BranchController::class,'index']);
Route::get('/branch/{branch}',[\App\Http\Controllers\BranchController::class,'show']);
Route::patch('/branch/{branch}',[\App\Http\Controllers\BranchController::class,'update']);
Route::delete('/branch/{branch}',[\App\Http\Controllers\BranchController::class,'delete']);

Route::post('/category',[\App\Http\Controllers\CategoryController::class,'create']);
Route::get('/category',[\App\Http\Controllers\CategoryController::class,'index']);
Route::get('/category/{category}',[\App\Http\Controllers\CategoryController::class,'show']);
Route::patch('/category/{category}',[\App\Http\Controllers\CategoryController::class,'update']);
Route::delete('/category/{category}',[\App\Http\Controllers\CategoryController::class,'delete']);



Route::post('/add-ticket/',[\App\Http\Controllers\TicketController::class,'create']);
Route::get('/get-ticket/',[\App\Http\Controllers\TicketController::class,'index']);
Route::get('/get-ticket/{ticket}',[\App\Http\Controllers\TicketController::class,'show']);
Route::get('/get_pending/',[\App\Http\Controllers\TicketController::class,'getPendingTicket']);
