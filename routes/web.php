<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\VotingController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CandidateCrudController;

// Voting Pages
Route::get('/', [VotingController::class, 'index'])->name('voting.index');
Route::post('/vote', [VotingController::class, 'vote'])->name('voting.submit');
Route::get('/thank-you', [VotingController::class, 'thankYou'])->name('voting.thank-you');

// Admin Auth Pages
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Dashboard Pages (requires authentication via 'admin' guard)
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/toggle-status', [AdminDashboardController::class, 'toggleStatus'])->name('toggle-status');
    Route::post('/reset-votes', [AdminDashboardController::class, 'resetVotes'])->name('reset-votes');
    Route::get('/export/excel', [AdminDashboardController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [AdminDashboardController::class, 'exportPdf'])->name('export.pdf');
    
    // Candidate Management CRUD
    Route::resource('candidates', CandidateCrudController::class);

    // Realtime voting results API
    Route::get('/realtime-results', [AdminDashboardController::class, 'getRealtimeResults'])->name('realtime-results');
});

// Public API endpoints (no auth — accessed by voter booths via IP)
Route::get('/api/voting-status', [VotingController::class, 'checkStatus'])->name('api.voting-status');
Route::get('/api/candidates', [VotingController::class, 'getCandidates'])->name('api.candidates');
