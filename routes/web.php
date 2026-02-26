<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;

// Registrasi rute autentikasi (login, register, logout)
Auth::routes();

// Rute utama ke landing page
Route::get('/', [HomeController::class, 'root'])->name('root');

Route::middleware('auth')->group(function () {
    // Halaman dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Backward compatibility
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Routes aset
    Route::get('/assets/loanable', [AssetController::class, 'loanable'])->name('assets.loanable');
    Route::get('/assets/export', [AssetController::class, 'exportExcel'])->name('assets.export');
    Route::get('/assets/import', [AssetController::class, 'importForm'])->name('assets.import.form');
    Route::post('/assets/import', [AssetController::class, 'import'])->name('assets.import');
    Route::get('/assets/template', [AssetController::class, 'exportTemplate'])->name('assets.template');
    Route::delete('/assets/{asset}/photo', [AssetController::class, 'destroyPhoto'])->name('assets.photo.destroy');
    Route::resource('assets', AssetController::class);

    // Routes peminjaman
    Route::post('/loans/batch', [LoanController::class, 'storeBatch'])->name('loans.store.batch');
    Route::get('/loans/{loan}/return', [LoanController::class, 'returnForm'])->name('loans.return.form');
    Route::put('/loans/{loan}/return', [LoanController::class, 'returnUpdate'])->name('loans.return.update');
    Route::get('/loans/receipt/{batch}', [LoanController::class, 'receipt'])->name('loans.receipt');
    Route::get('/loans/{loan}/return-receipt', [LoanController::class, 'returnReceipt'])->name('loans.return.receipt');
    Route::resource('loans', LoanController::class);

    // Routes laporan
    Route::get('/reports/loans', [ReportsController::class, 'loans'])->name('reports.loans');
    Route::get('/reports/loans/pdf', [ReportsController::class, 'loansPdf'])->name('reports.loans.pdf');
    Route::get('/reports/loans/excel', [ReportsController::class, 'loansExcel'])->name('reports.loans.excel');
    Route::get('/reports/returns', [ReportsController::class, 'returns'])->name('reports.returns');
    Route::get('/reports/returns/pdf', [ReportsController::class, 'returnsPdf'])->name('reports.returns.pdf');
    Route::get('/reports/returns/excel', [ReportsController::class, 'returnsExcel'])->name('reports.returns.excel');

    // Routes administrasi anggota
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset');
    Route::delete('/users/{user}/photo', [UserController::class, 'destroyPhoto'])->name('users.photo.destroy');
    Route::resource('users', UserController::class)->except('show');

    // Routes pengaturan landing
    Route::get('/settings/landing', [SettingController::class, 'landing'])->name('settings.landing');
    Route::post('/settings/landing', [SettingController::class, 'updateLanding'])->name('settings.landing.update');
});

// Jika kamu membutuhkan route dinamis untuk menangani URL lainnya
Route::get('{any}', [HomeController::class, 'index'])->name('index');
