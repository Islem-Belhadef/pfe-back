<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\RequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

Route::get('/requests', [RequestController::class, 'index'])->name('requests')->middleware('auth:sanctum');
Route::post('/request', [RequestController::class, 'store'])->name('add_request')->middleware('auth:sanctum');
Route::delete('/requests/destroy/{id}', [RequestController::class, 'destroy'])->name('destroy_request')->middleware('auth:sanctum');
Route::post('/requests/update/{id}', [RequestController::class, 'update'])->name('update_request')->middleware('auth:sanctum');

Route::get('/internships', [InternshipController::class, 'index'])->name('internships')->middleware('auth:sanctum');

Route::get('/users', [AccountController::class, 'index'])->name('users')->middleware('auth:sanctum');
Route::post('/users/new', [AccountController::class, 'store'])->name('create_hod')->middleware('auth:sanctum');
Route::delete('/users/{id}', [AccountController::class, 'destroy'])->name('delete')->middleware('auth:sanctum');

Route::post('/notation/{id}', [InternshipController::class, 'notation'])->name('notation');
Route::post('/presence/{id}', [InternshipController::class, 'presence'])->name('presence');
