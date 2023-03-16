<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\BookController;
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
Route::post('/search', [BookController::class,'search']);
Route::get('/genres', [BookController::class,'genres']);
Route::get('/product/{id}', [BookController::class,'product']);

Route::post('login', [AdminController::class, 'login']);
Route::post('logout', [AdminController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'books','middleware' => 'auth:sanctum'], function() {
    Route::get('/', [BookController::class,'index']);
    Route::post('add', [BookController::class,'add']);
    Route::post('update/{id}', [BookController::class,'update']);
    Route::get('edit/{id}', [BookController::class,'edit']);
    Route::delete('delete/{id}', [BookController::class,'delete']);
});
