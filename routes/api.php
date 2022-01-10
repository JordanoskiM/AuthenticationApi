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

//Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');

Route::group([
    'prefix' => 'auth'
], function() {
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/register/{userId}/complete-register', [\App\Http\Controllers\AuthController::class, 'completeRegister'])->name('register.complete');


});
Route::group([
    'middleware' => ['auth:sanctum']
], function() {
    Route::get('/send-email', [\App\Http\Controllers\EmailManagementController::class, 'sendEmail'])->name('sendEmail');
    Route::post('/update-user/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('update.user');
});


