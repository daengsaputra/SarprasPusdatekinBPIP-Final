<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ReportsController;
use App\Models\Asset;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Str;

// Landing page (public)
Route::get('/', function () {
    $summary = [
        'total' => Asset::where('kind', Asset::KIND_LOANABLE)->sum('quantity_total'),
        'available' => Asset::where('kind', Asset::KIND_LOANABLE)->sum('quantity_available'),
    ];
    $summary['in_use'] = max($summary['total'] - $summary['available'], 0);

    $availableAssets = Asset::select('code', 'name', 'category', 'quantity_available', 'photo')
        ->where('kind', Asset::KIND_LOANABLE)
        ->where('quantity_available', '>', 0)
        ->orderByDesc('quantity_available')
        ->get()
        ->groupBy(fn($asset) => Str::lower($asset->name))
        ->map(function ($group) {
            $primary = $group->sortByDesc('quantity_available')->first();
            $totalAvailable = (int) $group->sum('quantity_available');
            $categories = $group->pluck('category')->filter()->unique()->values();

            return (object) [
                'name' => $primary->name,
                'category' => $categories->count() > 1
                    ? $categories->implode(', ')
                    : ($categories->first() ?? 'Kategori belum diatur'),
                'photo' => $group->pluck('photo')->filter()->first(),
                'quantity_available' => $totalAvailable,
            ];
        })
        ->sortByDesc('quantity_available')
        ->values();

    $activeLoans = Loan::with('asset')
        ->whereIn('status', ['borrowed', 'partial'])
        ->orderByDesc('loan_date')
        ->get();

    return view('landing', compact('summary', 'availableAssets', 'activeLoans'));
})->name('landing');

// Authentication (public)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password reset (public)
Route::get('/password/forgot', [PasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/password/forgot', [PasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordController::class, 'reset'])->name('password.update');

// Data barang (public list)
Route::get('/assets/peminjaman', [AssetController::class, 'loanable'])->name('assets.loanable');
Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');

// Dashboard (protected)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {        
        $rawCounts = [
            'assets' => (int) Asset::where('kind', Asset::KIND_INVENTORY)->sum('quantity_total'),
            'assets_active' => (int) Asset::where('status', 'active')->where('kind', Asset::KIND_INVENTORY)->sum('quantity_total'),
            'assets_loanable' => (int) Asset::where('kind', Asset::KIND_LOANABLE)->sum('quantity_total'),
            'loans_active' => Loan::whereIn('status', ['borrowed','partial'])->count(),
            'loans_returned' => Loan::where('status', 'returned')->count(),
            'users' => User::count(),
        ];

        $dashboardCounts = [
            'assets_loanable' => (int) data_get($rawCounts, 'assets_loanable', 0),
            'assets' => (int) data_get($rawCounts, 'assets', 0),
            'users' => (int) data_get($rawCounts, 'users', 0),
            'loans_active' => (int) data_get($rawCounts, 'loans_active', 0),
            'loans_returned' => (int) data_get($rawCounts, 'loans_returned', 0),
        ];

        $loanTotalCount = Loan::count();
        $returnQuantityTotal = Loan::where('status', 'returned')->sum('quantity');
        $latestUsers = User::latest()->take(6)->get();

        return view('dashboard', compact('dashboardCounts', 'latestUsers', 'loanTotalCount', 'returnQuantityTotal'));
    })->name('dashboard');

    Route::resource('assets', AssetController::class)->except(['index'])->middleware('role:admin');
    Route::delete('/assets/{asset}/photo', [AssetController::class, 'destroyPhoto'])->name('assets.photo.destroy')->middleware('role:admin');
    Route::get('/assets-export', [AssetController::class, 'exportExcel'])->name('assets.export');
    Route::get('/assets-import', [AssetController::class, 'importForm'])->name('assets.import.form')->middleware('role:admin');
    Route::post('/assets-import', [AssetController::class, 'import'])->name('assets.import')->middleware('role:admin');
    Route::get('/assets-template', [AssetController::class, 'exportTemplate'])->name('assets.template');
    Route::resource('loans', LoanController::class)->except(['edit', 'update', 'show']);
    Route::get('loans/{loan}/return', [LoanController::class, 'returnForm'])->name('loans.return.form');
    Route::post('loans/{loan}/return', [LoanController::class, 'returnUpdate'])->name('loans.return.update');
    Route::post('loans/store-batch', [LoanController::class, 'storeBatch'])->name('loans.store.batch');
    Route::get('loans/receipt/{batch}', [LoanController::class, 'receipt'])->name('loans.receipt');
    Route::get('loans/return-receipt/{loan}', [LoanController::class, 'returnReceipt'])->name('loans.return.receipt');

    // Change password (authenticated)
    Route::get('/password/change', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/password/change', [PasswordController::class, 'change'])->name('password.change.post');

    // Reports
    Route::get('/reports/loans', [ReportsController::class, 'loans'])->name('reports.loans');
    Route::get('/reports/returns', [ReportsController::class, 'returns'])->name('reports.returns');
    Route::get('/reports/loans/pdf', [ReportsController::class, 'loansPdf'])->name('reports.loans.pdf');
    Route::get('/reports/returns/pdf', [ReportsController::class, 'returnsPdf'])->name('reports.returns.pdf');

    // Users (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/reset-password', [\App\Http\Controllers\UserController::class, 'resetPassword'])->name('users.reset');
        Route::delete('/users/{user}/photo', [\App\Http\Controllers\UserController::class, 'destroyPhoto'])->name('users.photo.destroy');
    });
});
