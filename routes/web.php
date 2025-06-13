<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/google/redirect', [AuthController::class, 'google_redirect'])->name('google_redirect');
Route::get('/auth/google/callback', [AuthController::class, 'google_callback'])->name('google_callback');

Route::middleware('auth')->group(function () {

    Route::get('register-expert', [AuthController::class, 'register'])->name('register_expert');
    Route::post('register-expert', [AuthController::class, 'register_expert_post'])->name('register_expert_post');

    Route::get('register-client', [AuthController::class, 'register'])->name('register_client');
    Route::post('register-client', [AuthController::class, 'register_client_post'])->name('register_client_post');

    Route::post('renew-password', [AuthController::class, 'renew_password'])->name('renew_password');
    Route::post('renew-picture', [AuthController::class, 'renew_picture'])->name('renew_picture');
    Route::get('update-profile', [AuthController::class, 'register'])->name('update_profile');
    Route::post('renew-profile', [AuthController::class, 'renew_profile'])->name('renew_profile');

    Route::get('profile', [AuthController::class, 'profile'])->name('profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('client-{slug_page}', [ClientController::class, 'home_client'])->name('home_client');
Route::get('client-{slug_page}/category-expert-{slug}', [ClientController::class, 'list_conselor'])->name('list_conselor');

Route::get('/expert-detail-{expert_id}', [ClientController::class, 'expert_detail'])->name('expert_detail');

Route::get('make-appointment', function () {
    return view('appointment');
})->name('appointment');

Route::get('/', function () {
    return view('client');
})->name('home');

Route::get('/expert', function () {
    return view('expert');
})->name('expert');

Route::get('/login', function () {
    return view('authentication.login');
})->name('login')->middleware('guest');

Route::post('/login', [AuthController::class, 'login_post'])->name('login_post')->middleware('guest');
