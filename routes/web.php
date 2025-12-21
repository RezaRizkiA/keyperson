<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\ClientRegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpertiseController;
use App\Http\Controllers\ExpertRegistrationController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// =========================================================================
// PUBLIC ROUTES (Tanpa Login)
// =========================================================================
Route::get('/', [LandingController::class, 'home'])->name('home');
Route::get('about', [LandingController::class, 'about'])->name('about');
Route::get('support', [LandingController::class, 'support'])->name('support');
Route::get('pricing', [LandingController::class, 'pricing'])->name('pricing');
Route::get('terms', [LandingController::class, 'terms'])->name('terms');
Route::get('privacy', [LandingController::class, 'privacy'])->name('privacy');

Route::prefix('portal')->group(function () {

    Route::get('/{client:slug_page}', [ClientPortalController::class, 'index'])
        ->name('client.home');
    Route::get('/{client:slug_page}/category/{expertise:slug}', [ClientPortalController::class, 'experts'])
        ->name('client.experts');
});
Route::get('/experts/{expert}', [ClientPortalController::class, 'show'])
    ->name('experts.show');

// =========================================================================
// AUTHENTICATION ROUTES (Guest Only)
// =========================================================================
Route::get('login', [AuthController::class, 'loginView'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login_post'])->name('login_post')->middleware('guest');

// Google Auth
Route::get('/auth/google/login', [AuthController::class, 'google_login'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'google_callback'])->name('google.callback');
Route::get('/auth/google/calendar-connect', [AuthController::class, 'google_calendar_connect'])->name('google.calendar.connect');

// Callback iPaymu (Luar auth group & csrf protected biasanya, tapi biarkan sesuai setup Anda)
Route::post('payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');


// =========================================================================
// PROTECTED ROUTES (Harus Login)
// =========================================================================
Route::middleware('auth')->group(function () {

    // ---------------------------------------------------------------------
    // 1. NEW DASHBOARD REFACTOR (Service-Repository Pattern)
    // ---------------------------------------------------------------------
    Route::prefix('dashboard')->group(function () {
        // Overview
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Appointments
        Route::prefix('appointments')->name('dashboard.appointments.')->group(function () {
            Route::get('/', [AppointmentController::class, 'index'])->name('index');
            Route::get('/{id}', [AppointmentController::class, 'show'])->name('show');
            Route::patch('/{id}/update-link', [AppointmentController::class, 'updateLink'])->name('update-link');

            // NEW ACTIONS
            Route::patch('/{id}/status', [AppointmentController::class, 'updateStatus'])->name('update-status');
            Route::patch('/{id}/reschedule', [AppointmentController::class, 'reschedule'])->name('reschedule');
            Route::delete('/{id}', [AppointmentController::class, 'destroy'])->name('destroy');
        });

        // Calendar
        Route::get('/calendar', [CalendarController::class, 'index'])->name('dashboard.calendar');

        // Billing/Transactions
        Route::get('/billing', [TransactionController::class, 'index'])->name('dashboard.billing');

        // Grouping Expertise Management (KHUSUS ADMIN)
        Route::prefix('expertises')->name('dashboard.expertises.')->group(function () {
            // 1. Halaman Utama
            Route::get('/', [ExpertiseController::class, 'index'])->name('index');

            // 2. CRUD Categories
            Route::post('/categories', [ExpertiseController::class, 'storeCategory'])->name('categories.store');
            Route::put('/categories/{id}', [ExpertiseController::class, 'updateCategory'])->name('categories.update');
            Route::delete('/categories/{id}', [ExpertiseController::class, 'destroyCategory'])->name('categories.destroy');

            // 3. CRUD Sub-Categories
            Route::post('/sub-categories', [ExpertiseController::class, 'storeSubCategory'])->name('sub-categories.store');
            Route::put('/sub-categories/{id}', [ExpertiseController::class, 'updateSubCategory'])->name('sub-categories.update');
            Route::delete('/sub-categories/{id}', [ExpertiseController::class, 'destroySubCategory'])->name('sub-categories.destroy');

            // 4. CRUD Skills
            Route::post('/skills', [ExpertiseController::class, 'storeSkill'])->name('skills.store');
            Route::put('/skills/{id}', [ExpertiseController::class, 'updateSkill'])->name('skills.update');
            Route::delete('/skills/{id}', [ExpertiseController::class, 'destroySkill'])->name('skills.destroy');
        });

        // Settings update profile
        Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    });

    Route::get('/booking/{expert}', [AppointmentController::class, 'create'])
        ->name('booking.create');
    Route::post('/booking', [AppointmentController::class, 'store'])
        ->name('booking.store');

    Route::get('/payment/{appointment}/checkout', [PaymentController::class, 'create'])
        ->name('payment.create');
    Route::post('/payment/{appointment}/checkout', [PaymentController::class, 'store'])
        ->name('payment.store');
    Route::get('/payment/transaction/{sid}', [PaymentController::class, 'transaction'])
        ->name('payment.transaction');
    Route::post('/payment/notify', [PaymentController::class, 'notify'])
        ->name('payment.notify');

    Route::prefix('expert-onboarding')->group(function () {
        // Halaman Form Registrasi
        Route::get('/', [ExpertRegistrationController::class, 'create'])
            ->name('expert_onboarding.create');

        // Proses Simpan Data
        Route::post('/', [ExpertRegistrationController::class, 'store'])
            ->name('expert_onboarding.store');
    });

    Route::prefix('client-onboarding')->group(function () {
        // 1. Halaman Form Registrasi Client
        Route::get('/', [ClientRegistrationController::class, 'create'])
            ->name('client_onboarding.create');

        // 2. Proses Simpan Data Client
        Route::post('/', [ClientRegistrationController::class, 'store'])
            ->name('client_onboarding.store');
    });

    // ---------------------------------------------------------------------
    // 2. LEGACY ROUTES (Existing Functionality - Jangan Dihapus)
    // ---------------------------------------------------------------------
    // Auth & Profile Legacy
    // Route::get('register-client', [AuthController::class, 'registerClient'])->name('register_client');
    // Route::post('register-client', [AuthController::class, 'register_client_post'])->name('register_client_post');

    // Route ini bentrok secara konsep dengan 'profile.edit' di atas, tapi URL-nya beda jadi aman.
    Route::get('update-profile', [AuthController::class, 'settings'])->name('update_profile');
    Route::post('renew-picture', [AuthController::class, 'renew_picture'])->name('renew_picture');
    Route::post('renew-password', [AuthController::class, 'renew_password'])->name('renew_password');
    Route::post('renew-profile', [AuthController::class, 'renew_profile'])->name('renew_profile');

    // Route Dashboard LAMA (Controller LAMA)
    // Masih bisa diakses via URL /profile jika ada link lama yang mengarah ke sini
    // Route::get('profile', [AuthController::class, 'profile'])->name('profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    if (app()->isLocal()) {
        Route::get('paymentnotify-test', function () {
            return view('payment.notify_test');
        })->name('payment.notify_test');
    }
});
