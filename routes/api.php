<?php

use App\Http\Controllers\UserController;
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


Route::get('/home',[UserController::class,'home'])->name('home');

Route::post('/signup/attende', [UserController::class, 'SignupAsAttende'])->name('SignupAsAttende');

Route::post('/signup/orgnizer', [UserController::class, 'SignupAsOrgnizer'])->name('SignupAsOrgnizer')->middleware('EnsureTokenIsValid');

route::group(['middleware'=>'EnsureTokenIsValid'],function(){
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::post('/check',[UserController::class,'index'])->name('check')->middleware('authCheck');
    Route::post('/update_user_profile/{id}',[UserController::class,'update_profile'])->name('update_profile')->middleware('authCheck');

});


