<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuditAuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/audit/login', [AuditAuthController::class, 'showLogin']);
Route::post('/audit/login', [AuditAuthController::class, 'login']);
Route::post('/audit/logout', [AuditAuthController::class, 'logout']);

Route::middleware('audit.auth')->group(function () {
    Route::get('/audit/dashboard', [AuditAuthController::class, 'dashboard']);
    Route::get('/audit/5s-standard', [AuditAuthController::class, 'standard5s']);
    Route::get('/audit/5s-standard/{area}', [AuditAuthController::class, 'standard5sProcess']);
    Route::get('/audit/change-point-management', [AuditAuthController::class, 'changePointManagement']);
    Route::get('/audit/license-system', [AuditAuthController::class, 'licenseSystem']);
    Route::get('/audit/riwayat', [AuditAuthController::class, 'riwayat']);
    Route::get('/audit/pedoman', [AuditAuthController::class, 'pedoman']);
    Route::get('/audit/placeholder', [AuditAuthController::class, 'placeholder']);
});