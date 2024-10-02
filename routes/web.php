<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\EditkeluargaController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// ***************** Home Route *****************
Route::get('/', function () {
    return view('index');
})->name('home');

// ***************** Dashboard *****************
Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->name('dashboard')
    ->middleware('auth');

// ***************** Routes Pemasukan *****************
Route::middleware('auth')->group(function () {
    Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan');
    Route::get('/view-pemasukan', [PemasukanController::class, 'view'])->name('view-pemasukan');

    // Route yang hanya bisa diakses oleh admin dan bendahara
    Route::middleware('role:admin,bendahara')->group(function () {
        Route::post('/view-pemasukan', [PemasukanController::class, 'store'])->name('add-pemasukan');
        Route::get('/pemasukan-edit/{id}', [PemasukanController::class, 'edit']);
        Route::post('/pemasukan-update/{id}', [PemasukanController::class, 'update']);
        Route::delete('/pemasukan-delete/{id}', [PemasukanController::class, 'destroy']);
    });
});


// ***************** Routes Edit Keluarga *****************
Route::middleware('auth')->group(function () {
    Route::middleware(['role:isadmin|isbendahara'])->group(function () {
        // Routes accessible by both admin and bendahara
        Route::get('/informasikeluarga',  [EditkeluargaController::class, 'view'])->name('informasi-keluarga');
        Route::get('/editkeluarga', [EditkeluargaController::class, 'index'])->name('edit-keluarga');
        Route::post('/editkeluarga/update', [EditkeluargaController::class, 'update'])->name('edit-keluarga.update');
    });
});

// ***************** Routes Pengeluaran *****************
Route::middleware('auth')->group(function () {
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran');
    Route::get('/view-pengeluaran', [PengeluaranController::class, 'view'])->name('view-pengeluaran');
    Route::post('/view-pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.post');

    // Route yang hanya bisa diakses oleh admin dan bendahara
    Route::middleware(['auth', 'isadmin'])->group(function () {
        Route::get('/pengeluaran-edit/{id}', [PengeluaranController::class, 'edit']);
        Route::post('/pengeluaran-update/{id}', [PengeluaranController::class, 'update']);
        Route::delete('/pengeluaran-delete/{id}', [PengeluaranController::class, 'destroy']);
    });
});

// ***************** Routes Login *****************
Route::middleware('guest')->group(function () {
    Route::get('/account/login', [AuthenticationController::class, 'index'])->name('account.login');
    Route::post('/dashboard', [AuthenticationController::class, 'authenticate'])->name('account.authenticate');
    Route::get('/account/login', [AuthenticationController::class, 'index'])->name('login');
});

// ***************** Routes Logout *****************
Route::post('/account/logout', [AuthenticationController::class, 'logout'])->name('account.logout')->middleware('auth');

// ***************** Routes Register *****************
Route::middleware(['auth', 'isadmin'])->group(function () {
    Route::get('/account/register', [AuthenticationController::class, 'formRegister'])->name('account.register');
    Route::post('/account/process-register', [AuthenticationController::class, 'processRegister'])->name('account.processregister');
});

// ***************** Route Laporan *****************
Route::middleware('auth')->group(function () {
    Route::middleware(['role:isadmin|isbendahara'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan/export', [LaporanController::class, 'export_excel'])->name('laporan.export');
});
});


// ***************** Routes Users *****************
Route::middleware(['auth', 'isadmin'])->group(function () {
    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::get('/users-edit/{id}', [UsersController::class, 'edit'])->name('users.edit');
    Route::post('/users-update/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users-delete/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
});



// ***************** Route Error 404 *****************
// Route::fallback(function () {
//     return view('errors.404');
// });
