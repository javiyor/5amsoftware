<?php

use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DemoRequestController as AdminDemoRequestController;
use App\Http\Controllers\Admin\SystemController as AdminSystemController;
use App\Http\Controllers\Admin\SystemPlanController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Public\DemoRequestController;
use App\Http\Controllers\Public\SeoController;
use App\Http\Controllers\Public\SystemController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/sistemas/{system:slug}', [SystemController::class, 'show'])->name('systems.show');
Route::post('/demos', [DemoRequestController::class, 'store'])
    ->middleware('throttle:demo')
    ->name('demo.store');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:login')
        ->name('login.attempt');

    Route::get('/olvide-contrasena', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/olvide-contrasena', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->middleware('throttle:login')
        ->name('password.email');

    Route::get('/restablecer-contrasena/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/restablecer-contrasena', [ResetPasswordController::class, 'reset'])
        ->middleware('throttle:login')
        ->name('password.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/verificar-correo', [VerifyEmailController::class, 'show'])
        ->name('verification.notice');
    Route::get('/verificar-correo/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('/verificar-correo/enviar', [VerifyEmailController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/cambiar-contrasena', [ChangePasswordController::class, 'showChangePasswordForm'])
        ->name('password.change');
    Route::post('/cambiar-contrasena', [ChangePasswordController::class, 'change'])
        ->name('password.change.update');
});

Route::prefix('admin')
    ->middleware(['auth', 'user.active', 'role.admin'])
    ->name('admin.')
    ->group(function (): void {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('customers', CustomerController::class);

        Route::resource('contracts', ContractController::class);

        Route::get('demos', [AdminDemoRequestController::class, 'index'])->name('demos.index');
        Route::get('demos/{demo}', [AdminDemoRequestController::class, 'show'])->name('demos.show');
        Route::put('demos/{demo}/status', [AdminDemoRequestController::class, 'updateStatus'])->name('demos.update-status');

        Route::resource('systems', AdminSystemController::class);
        Route::post('systems/{system}/features', [AdminSystemController::class, 'storeFeature'])->name('systems.features.store');
        Route::delete('systems/{system}/features/{feature}', [AdminSystemController::class, 'destroyFeature'])->name('systems.features.destroy');
        Route::post('systems/{system}/plans', [SystemPlanController::class, 'store'])->name('plans.store');
        Route::get('systems/{system}/plans/{plan}/edit', [SystemPlanController::class, 'edit'])->name('plans.edit');
        Route::put('systems/{system}/plans/{plan}', [SystemPlanController::class, 'update'])->name('plans.update');
        Route::delete('systems/{system}/plans/{plan}', [SystemPlanController::class, 'destroy'])->name('plans.destroy');
    });

Route::prefix('cliente')
    ->middleware(['auth', 'user.active', 'role.customer', 'verified'])
    ->name('client.')
    ->group(function (): void {
        Route::get('/', [ClientDashboardController::class, 'index'])->name('dashboard');
    });
