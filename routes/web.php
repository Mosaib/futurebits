<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentAuthController;
use App\Http\Controllers\AdminWithdrawalController;


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [AdminWithdrawalController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboardm', [AdminWithdrawalController::class, 'index'])->name('admin.dashboard');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('admin.withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('admin.withdrawals.reject');
});



Route::prefix('agent')->name('agent.')->group(function () {
    Route::get('login', [AgentAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AgentAuthController::class, 'login']);
    Route::get('register', [AgentAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AgentAuthController::class, 'register']);
    Route::post('logout', [AgentAuthController::class, 'logout'])->name('logout');
    Route::middleware('auth:agent')->group(function () {
        Route::get('dashboard', [AgentAuthController::class, 'dashboard'])->name('dashboard');
        Route::post('withdraw', [AgentAuthController::class, 'requestWithdrawal'])->name('withdraw.request');
    });
});


require __DIR__.'/auth.php';
