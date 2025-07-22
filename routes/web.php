<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\ExpertiseController;
use App\Http\Controllers\PaymentController; // Ditambahkan
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

    Route::get('make-appointment-{expert_id}', [ExpertController::class, 'make_appointment'])->name('appointment');
    Route::post('make-appointment-{expert_id}', [ExpertController::class, 'make_appointment_post'])->name('appointment_post');
    Route::post('appointment/{id}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointment.update_status');
    Route::put('/appointments/{id}/edit-schedule', [AppointmentController::class, 'editSchedule'])->name('appointment.edit_schedule');

    // Rute Pembayaran
    Route::get('payment/{appointment}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('payment/{appointment}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('payment-{sid}', [PaymentController::class, 'transaction'])->name('payment.transaction');
    Route::post('payment-notify', [PaymentController::class, 'notify'])->name('payment.notify');

    Route::get('paymentnotify-test', function () {
        return view('payment.notify_test');
    })->name('payment.notify_test');

    Route::post('create-expertise', [ExpertiseController::class, 'store_expertise'])->name('store_expertise');
    Route::post('create-expertise-{expertise_id}', [ExpertiseController::class, 'update_expertise'])->name('update_expertise');
    Route::get('destroy-expertise-{expertise_id}', [ExpertiseController::class, 'destroy_expertise'])->name('destroy_expertise');

});

Route::get('client-{slug_page}', [ClientController::class, 'home_client'])->name('home_client');
Route::get('client-{slug_page}/expertise-{slug}', [ClientController::class, 'list_conselor'])->name('list_conselor');

Route::get('/expert-detail-{expert_id}', [ClientController::class, 'expert_detail'])->name('expert_detail');

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('about', function () {
    return view('about');
})->name('about');

Route::get('support', function () {
    return view('support');
})->name('support');

Route::get('terms', function () {
    return view('terms');
})->name('terms');

Route::get('privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('login', function () {
    return view('authentication.login');
})->name('login')->middleware('guest');

Route::post('/login', [AuthController::class, 'login_post'])->name('login_post')->middleware('guest');

// Callback iPaymu - Rute ini di luar grup 'web' untuk menghindari masalah CSRF
Route::post('payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
