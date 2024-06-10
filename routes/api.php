<?php

use Illuminate\Http\Request;
use PHPUnit\Metadata\PostCondition;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Authcontroller as ControllersAuthcontroller;

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

Route::post('login',[AuthController::class,'login'])->name('login');
Route::post('register',[AuthController::class,'register'])->name('register');
Route::post('logout',[AuthController::class,'logout'])->name('logout')->middleware('auth:sanctum');


Route::group(['prefix'=>'v1', 'middleware'=>'auth:sanctum'], function(){

Route::apiResource('posts',PostController::class);
Route::apiResource('users',UserController::class);
Route::get('/posts/search/{name}',[PostController::class,'search']);

});

