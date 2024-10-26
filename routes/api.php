<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
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

// 인증관련
Route::prefix('auth')->name('auth.')->group(function () {
    // 로그인
    Route::post('/login', [AuthController::class, 'login']);

    // 회원가입
    Route::post('/register', [AuthController::class, 'register']);

    // 로그아웃
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});