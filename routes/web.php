<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HelpCenterController;
use App\Http\Controllers\Admin\SwaggerDocController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Super Admin Web Authentication
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Super Admin Protected Web Portal
Route::middleware(['auth', EnsureSuperAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logs', [DashboardController::class, 'logs'])->name('logs');
    Route::get('/reservations', [DashboardController::class, 'reservations'])->name('reservations');

    // Manajemen Banner Iklan & Promosi
    Route::resource('banners', BannerController::class)->except(['show']);
    Route::post('banners/{banner}/toggle', [BannerController::class, 'toggle'])->name('banners.toggle');

    // Pengaturan Tema & Warna Aplikasi Mobile
    Route::get('/theme', [ThemeController::class, 'index'])->name('theme.index');
    Route::post('/theme', [ThemeController::class, 'update'])->name('theme.update');
    Route::post('/theme/reset', [ThemeController::class, 'reset'])->name('theme.reset');

    // Pusat Bantuan & Kontak Rumah Sakit
    Route::get('/help-center', [HelpCenterController::class, 'index'])->name('help_center.index');
    Route::post('/help-center/contacts', [HelpCenterController::class, 'updateContacts'])->name('help_center.contacts');
    Route::post('/help-center/faq', [HelpCenterController::class, 'storeFaq'])->name('help_center.faq.store');
    Route::put('/help-center/faq/{faq}', [HelpCenterController::class, 'updateFaq'])->name('help_center.faq.update');
    Route::delete('/help-center/faq/{faq}', [HelpCenterController::class, 'deleteFaq'])->name('help_center.faq.delete');

    // Dokumentasi & API Tester (Swagger UI)
    Route::get('/swagger', [SwaggerDocController::class, 'index'])->name('swagger.index');
    Route::get('/swagger/openapi.json', [SwaggerDocController::class, 'openApiJson'])->name('swagger.json');
});
