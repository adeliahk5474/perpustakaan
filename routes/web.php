<?php
// routes/web.php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;

// ── PUBLIC ROUTES ──────────────────────────────────────────────────────────
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/books/{book}', [CatalogController::class, 'show'])->name('books.show');

// ── AUTH ROUTES ────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── MEMBER ROUTES ──────────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/my-books', [LoanController::class, 'dashboard'])->name('member.dashboard');
    Route::get('/my-books/history', [LoanController::class, 'history'])->name('member.history');
    Route::post('/books/{book}/borrow', [LoanController::class, 'borrow'])->name('loans.borrow');
    Route::post('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');
    Route::post('/loans/{loan}/renew', [LoanController::class, 'renew'])->name('loans.renew');
    Route::post('/books/{book}/wishlist', [LoanController::class, 'toggleWishlist'])->name('books.wishlist');
});

// ── ADMIN ROUTES ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Books CRUD
    Route::get('/books', [AdminController::class, 'books'])->name('books');
    Route::get('/books/create', [AdminController::class, 'createBook'])->name('books.create');
    Route::post('/books', [AdminController::class, 'storeBook'])->name('books.store');
    Route::get('/books/{book}/edit', [AdminController::class, 'editBook'])->name('books.edit');
    Route::put('/books/{book}', [AdminController::class, 'updateBook'])->name('books.update');
    Route::delete('/books/{book}', [AdminController::class, 'destroyBook'])->name('books.destroy');

    // Members
    Route::get('/members', [AdminController::class, 'members'])->name('members');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::patch('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::patch('/settings/account', [SettingController::class, 'updateAccount'])->name('settings.account');

    // Loans
    Route::get('/loans', [AdminController::class, 'loans'])->name('loans');
    Route::post('/loans/{loan}/confirm-return', [AdminController::class, 'confirmReturn'])->name('loans.confirm-return');
});
