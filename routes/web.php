<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/audit/login', fn() => view('audit.login'));
Route::get('/audit/dashboard', fn() => view('audit.dashboard'));
