<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuditAuthController;
use App\Http\Controllers\AuditDashboardController;
use App\Http\Controllers\AuditProcessController;

// Redirect root langsung ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/audit/login', [AuditAuthController::class, 'showLogin'])->name('login');
Route::post('/audit/login', [AuditAuthController::class, 'login']);
Route::post('/audit/logout', [AuditAuthController::class, 'logout']);

// Protected Routes
Route::middleware('audit.auth')->group(function () {
    Route::get('/audit/dashboard', [AuditDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/audit/riwayat', [AuditDashboardController::class, 'riwayat'])->name('riwayat');
    Route::get('/audit/riwayat/{id}', [AuditDashboardController::class, 'riwayatDetail'])->name('audit.riwayat.detail');
    Route::get('/audit/pedoman', [AuditDashboardController::class, 'pedoman']);
    Route::get('/audit/placeholder', [AuditDashboardController::class, 'placeholder']);

    Route::get('/audit/5s-standard', [AuditProcessController::class, 'standard5s']);
    Route::get('/audit/5s-standard/{area}', [AuditProcessController::class, 'standard5sProcess']);
    Route::get('/audit/change-point-management', [AuditProcessController::class, 'changePointManagement']);
    Route::get('/audit/license-system', [AuditProcessController::class, 'licenseSystem']);
    
    // Fitur Form Audit Process & Penilaian (OK / NG)
    Route::get('/audit/process/{process}/form', [AuditProcessController::class, 'showAuditForm']);
    Route::post('/audit/process/{process}/submit', [AuditProcessController::class, 'submitAuditForm']);
});