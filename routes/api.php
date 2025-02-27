<?php

use Illuminate\Http\Request;
use PHPUnit\Metadata\PostCondition;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Authcontroller as ControllersAuthcontroller;
use Illuminate\Http\Response;

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

// Route::get('/{any}', function () {
//     return view('welcome'); // Make sure you have resources/views/app.blade.php
// })->where('any', '.*');


Route::post('login',[AuthController::class,'login'])->name('login');
Route::post('register',[AuthController::class,'register'])->name('register');

Route::post('forgotpassword', [AuthController::class,'forgotpassword'])->name('forgotpassword');
Route::get('passwordreset/{token}',[AuthController::class, 'passwordreset'])->name('password.reset');
Route::post('passwordstore',[AuthController::class, 'passwordstore'])->name('password.store');


Route::get('/v1/posts', [PostController::class, 'index']);
Route::get('/v1/posts/{id}', [PostController::class, 'show']);
Route::group(['prefix'=>'v1', 'middleware'=>'auth:sanctum'], function(){


Route::get('/user', function (Request $request) { return $request->user(); });
Route::apiResource('posts', PostController::class)->except(['index', 'show']);
Route::apiResource('users',UserController::class);
Route::get('/posts/search/{searchword}',[PostController::class,'search']);
Route::post('/posts/like/{id}',[PostController::class,'like']);
Route::post('/posts/{id}/comment',[PostController::class,'comment']);
Route::post('logout',[AuthController::class,'logout'])->name('logout');

});

Route::get("/test-me", function () {
    return 'Hello from Laravel!';
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Route Not Found. If error persists, contact Kudam775@gmail.com'],
    Response::HTTP_NOT_FOUND);
});
