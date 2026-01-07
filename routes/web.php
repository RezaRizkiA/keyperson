<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\ClientRegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeInviteController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\ExpertiseController;
use App\Http\Controllers\ExpertRegistrationController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// =========================================================================
// PUBLIC ROUTES (Tanpa Login)
// =========================================================================
Route::get('/', [LandingController::class, 'home'])->name('home');
Route::get('about', [LandingController::class, 'about'])->name('about');
Route::get('services', [LandingController::class, 'services'])->name('services');
Route::get('contact', [LandingController::class, 'contact'])->name('contact');
Route::get('support', [LandingController::class, 'support'])->name('support');
Route::get('pricing', [LandingController::class, 'pricing'])->name('pricing');
Route::get('terms', [LandingController::class, 'terms'])->name('terms');
Route::get('privacy', [LandingController::class, 'privacy'])->name('privacy');

Route::prefix('portal')->middleware('client-access')->group(function () {
    Route::get('/{client:slug}', [ClientPortalController::class, 'index'])
        ->name('client.home');
    Route::get('/{client:slug}/category/{expertise:slug}', [ClientPortalController::class, 'experts'])
        ->name('client.experts');
});

Route::get('/experts/{slug}', [ClientPortalController::class, 'show'])
    ->name('experts.show');

// Choose Path Page
Route::get('/choose-path', [AuthController::class, 'choosePath'])->name('choose_path');

Route::get('login', [AuthController::class, 'loginView'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login_post'])->name('login_post')->middleware('guest');

// Google Auth
Route::get('/auth/google/login', [AuthController::class, 'google_login'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'google_callback'])->name('google.callback');
Route::get('/auth/google/calendar-connect', [AuthController::class, 'google_calendar_connect'])->name('google.calendar.connect');

// Callback iPaymu (Luar auth group & csrf protected biasanya, tapi biarkan sesuai setup Anda)
Route::post('payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

// Employee Invite (Public - for invited users to register)
Route::get('/join/{token}', [EmployeeInviteController::class, 'verify'])->name('invite.verify');
Route::post('/join/{token}', [EmployeeInviteController::class, 'register'])->name('invite.register');

// =========================================================================
// AUTH ROUTES (Login)
// =========================================================================
Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::prefix('appointments')->name('dashboard.appointments.')->group(function () {
            Route::get('/', [AppointmentController::class, 'index'])->name('index');
            Route::get('/{id}', [AppointmentController::class, 'show'])->name('show');
            Route::patch('/{id}/update-link', [AppointmentController::class, 'updateLink'])->name('update-link');
            Route::patch('/{id}/status', [AppointmentController::class, 'updateStatus'])->name('update-status');
            Route::patch('/{id}/reschedule', [AppointmentController::class, 'reschedule'])->name('reschedule');
            Route::delete('/{id}', [AppointmentController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('experts')->name('dashboard.experts.')->group(function () {
            Route::get('/', [ExpertController::class, 'index'])->name('index');
            Route::get('/{id}', [ExpertController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ExpertController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ExpertController::class, 'update'])->name('update');
            Route::delete('/{id}', [ExpertController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('clients')->name('dashboard.clients.')->group(function () {
            Route::get('/', [ClientController::class, 'index'])->name('index');
            Route::get('/{id}', [ClientController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ClientController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ClientController::class, 'update'])->name('update');
            Route::delete('/{id}', [ClientController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('users')->name('dashboard.users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::get('/transactions', [TransactionController::class, 'index'])->name('dashboard.transactions.index');

        Route::prefix('expertises')->name('dashboard.expertises.')->group(function () {
            Route::get('/', [ExpertiseController::class, 'index'])
                ->name('index');
            Route::post('/categories', [ExpertiseController::class, 'storeCategory'])
                ->name('categories.store');
            Route::put('/categories/{id}', [ExpertiseController::class, 'updateCategory'])
                ->name('categories.update');
            Route::delete('/categories/{id}', [ExpertiseController::class, 'destroyCategory'])
                ->name('categories.destroy');
            Route::post('/sub-categories', [ExpertiseController::class, 'storeSubCategory'])
                ->name('sub-categories.store');
            Route::put('/sub-categories/{id}', [ExpertiseController::class, 'updateSubCategory'])
                ->name('sub-categories.update');
            Route::delete('/sub-categories/{id}', [ExpertiseController::class, 'destroySubCategory'])
                ->name('sub-categories.destroy');
            Route::post('/skills', [ExpertiseController::class, 'storeSkill'])
                ->name('skills.store');
            Route::put('/skills/{id}', [ExpertiseController::class, 'updateSkill'])
                ->name('skills.update');
            Route::delete('/skills/{id}', [ExpertiseController::class, 'destroySkill'])
                ->name('skills.destroy');
        });

        Route::get('/settings', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        // === PROFILE UPDATE ROUTES ===
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::post('/update', [ProfileController::class, 'update'])->name('update');
            Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
            Route::post('/picture', [ProfileController::class, 'updatePicture'])->name('picture.update');
        });

        // === HRD EMPLOYEE MANAGEMENT (Client role only) ===
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', [EmployeeInviteController::class, 'index'])->name('index');
            Route::post('/invite', [EmployeeInviteController::class, 'store'])->name('invite');
            Route::delete('/invite/{id}', [EmployeeInviteController::class, 'destroy'])->name('revoke');
        });
    });

    // Booking routes - Expert tidak bisa booking
    Route::middleware('not-expert')->group(function () {
        Route::get('/booking/{slug}', [AppointmentController::class, 'create'])
            ->name('booking.create');
        Route::post('/booking', [AppointmentController::class, 'store'])
            ->name('booking.store');
    });

    Route::get('/payment/{appointment}/checkout', [PaymentController::class, 'create'])
        ->name('payment.create');
    Route::post('/payment/{appointment}/checkout', [PaymentController::class, 'store'])
        ->name('payment.store');
    Route::get('/payment/transaction/{sid}', [PaymentController::class, 'transaction'])
        ->name('payment.transaction');
    Route::post('/payment/notify', [PaymentController::class, 'notify'])
        ->name('payment.notify');

    // === ONBOARDING ROUTES ===
    // Middleware: Block jika sudah Expert, Client, atau Corporate Employee
    Route::middleware('eligible-onboarding')->group(function () {
        Route::prefix('expert-onboarding')->group(function () {
            Route::get('/', [ExpertRegistrationController::class, 'create'])
                ->name('expert_onboarding.create');
            Route::post('/', [ExpertRegistrationController::class, 'store'])
                ->name('expert_onboarding.store');
        });

        Route::prefix('client-onboarding')->group(function () {
            Route::get('/', [ClientRegistrationController::class, 'create'])
                ->name('client_onboarding.create');
            Route::post('/', [ClientRegistrationController::class, 'store'])
                ->name('client_onboarding.store');
        });
    });
    // === END ONBOARDING ROUTES ===

    // === B2B TOP-UP ROUTES ===
    Route::prefix('topup')->name('topup.')->group(function () {
        Route::get('/', [TopUpController::class, 'create'])->name('create');
        Route::post('/', [TopUpController::class, 'store'])->name('store');
        Route::get('/success', [TopUpController::class, 'success'])->name('success');
        Route::get('/history', [TopUpController::class, 'history'])->name('history');
    });
    // === END B2B TOP-UP ===

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    if (app()->isLocal()) {
        Route::get('paymentnotify-test', function () {
            return view('payment.notify_test');
        })->name('payment.notify_test');
    }
});
