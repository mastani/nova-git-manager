<?php

use Illuminate\Support\Facades\Route;
use Mastani\NovaGitManager\Http\Controllers\NovaGitController;

Route::get('branches', [NovaGitController::class, 'branches']);
Route::get('log', [NovaGitController::class, 'log']);
Route::post('pull', [NovaGitController::class, 'pull']);
Route::post('checkout', [NovaGitController::class, 'checkout']);
