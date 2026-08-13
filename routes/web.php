<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuditScheduleController;
use App\Http\Controllers\Admin\AuditTypeController;
use App\Http\Controllers\Admin\AuditUserManagementController;
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
    Route::get('/audit/riwayat/export', [AuditDashboardController::class, 'exportRiwayat'])->name('riwayat.export');
    Route::get('/audit/riwayat/{id}', [AuditDashboardController::class, 'riwayatDetail'])->name('audit.riwayat.detail');
    Route::get('/audit/riwayat/{id}/edit', [AuditDashboardController::class, 'riwayatEdit'])->name('audit.riwayat.edit');
    Route::put('/audit/riwayat/{id}', [AuditDashboardController::class, 'riwayatUpdate'])->name('audit.riwayat.update');
    Route::get('/audit/pedoman', [AuditDashboardController::class, 'pedoman'])->name('audit.pedoman');
    Route::post('/audit/pedoman/{process}/upload', [AuditProcessController::class, 'uploadPedoman'])->name('audit.pedoman.upload');
    Route::get('/audit/placeholder', [AuditDashboardController::class, 'placeholder']);

    Route::get('/audit/5s-standard', [AuditProcessController::class, 'standard5s']);
    Route::get('/audit/5s-standard/{area}', [AuditProcessController::class, 'standard5sProcess']);
    Route::get('/audit/change-point-management', [AuditProcessController::class, 'changePointManagement']);
    Route::get('/audit/license-system', [AuditProcessController::class, 'licenseSystem']);
    
    // Fitur Form Audit Process & Penilaian (OK / NG)
    Route::get('/audit/process/{process}/form', [AuditProcessController::class, 'showAuditForm']);
    Route::post('/audit/process/{process}/submit', [AuditProcessController::class, 'submitAuditForm']);

    // Admin - Manajemen Jenis Audit
    Route::get('/audit/admin/jenis-audit', [AuditTypeController::class, 'index'])->name('admin.jenis-audit.index');
    Route::get('/audit/admin/jenis-audit/create', [AuditTypeController::class, 'create'])->name('admin.jenis-audit.create');
    Route::post('/audit/admin/jenis-audit', [AuditTypeController::class, 'store'])->name('admin.jenis-audit.store');
    Route::get('/audit/admin/jenis-audit/{id}/edit', [AuditTypeController::class, 'edit'])->name('admin.jenis-audit.edit');
    Route::put('/audit/admin/jenis-audit/{id}', [AuditTypeController::class, 'update'])->name('admin.jenis-audit.update');
    Route::delete('/audit/admin/jenis-audit/{id}', [AuditTypeController::class, 'destroy'])->name('admin.jenis-audit.destroy');

    // Admin - Kelola Akun Auditor
    Route::get('/audit/admin/auditor', [AuditUserManagementController::class, 'index'])->name('admin.auditor.index');
    Route::get('/audit/admin/auditor/create', [AuditUserManagementController::class, 'create'])->name('admin.auditor.create');
    Route::post('/audit/admin/auditor', [AuditUserManagementController::class, 'store'])->name('admin.auditor.store');
    Route::get('/audit/admin/auditor/{id}/edit', [AuditUserManagementController::class, 'edit'])->name('admin.auditor.edit');
    Route::put('/audit/admin/auditor/{id}', [AuditUserManagementController::class, 'update'])->name('admin.auditor.update');
    Route::delete('/audit/admin/auditor/{id}', [AuditUserManagementController::class, 'destroy'])->name('admin.auditor.destroy');

    // Admin - CRUD Jadwal Audit
    Route::get('/audit/admin/jadwal', [AuditScheduleController::class, 'index'])->name('admin.jadwal.index');
    Route::get('/audit/admin/jadwal/create', [AuditScheduleController::class, 'create'])->name('admin.jadwal.create');
    Route::post('/audit/admin/jadwal', [AuditScheduleController::class, 'store'])->name('admin.jadwal.store');
    Route::get('/audit/admin/jadwal/{id}/edit', [AuditScheduleController::class, 'edit'])->name('admin.jadwal.edit');
    Route::put('/audit/admin/jadwal/{id}', [AuditScheduleController::class, 'update'])->name('admin.jadwal.update');
    Route::delete('/audit/admin/jadwal/{id}', [AuditScheduleController::class, 'destroy'])->name('admin.jadwal.destroy');
});