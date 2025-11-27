<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('index'); // nanti kita bikin resources/views/index.blade.php
})->name('dashboard');

// Route untuk test Octane
Route::get('/test-octane', function () {
    return response()->json([
        'message' => 'Halo! Laravel Octane dengan FrankenPHP berhasil jalan!',
        'server' => 'FrankenPHP',
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'timestamp' => now()->format('Y-m-d H:i:s'),
        'memory_usage' => round(memory_get_usage() / 1024 / 1024, 2) . ' MB',
    ]);
});

// Route untuk test performa
Route::get('/test-performance', function () {
    $start = microtime(true);
    
    // Simulasi kerja berat: buat 1000 array
    $data = [];
    for ($i = 0; $i < 1000; $i++) {
        $data[] = [
            'id' => $i,
            'name' => 'User ' . $i,
            'email' => 'user' . $i . '@example.com',
            'created_at' => now(),
        ];
    }
    
    $end = microtime(true);
    $executionTime = ($end - $start) * 1000; // Convert ke milliseconds
    
    return response()->json([
        'message' => 'Test performance selesai!',
        'data_generated' => count($data),
        'execution_time' => round($executionTime, 2) . ' ms',
        'memory_used' => round(memory_get_usage() / 1024 / 1024, 2) . ' MB',
    ]);
});


//Karyawan
Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
Route::put('/karyawan/{karyawan}', [KaryawanController::class, 'update'])->name('karyawan.update');
Route::delete('/karyawan/{karyawan}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');

//gaji
Route::get('/gaji', [GajiController::class, 'index'])->name('gaji.index');
Route::post('/gaji', [GajiController::class, 'store'])->name('gaji.store');
Route::put('/gaji/{gaji}', [GajiController::class, 'update'])->name('gaji.update');
Route::delete('/gaji/{gaji}', [GajiController::class, 'destroy'])->name('gaji.destroy');


// Auth Routes
// halaman login hanya bisa diakses tamu (belum login)
Route::middleware('guest')->group(function () {
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin']);
});

// logout hanya bisa diakses user yang sudah login
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ========== HALAMAN YANG WAJIB LOGIN ==========

Route::middleware('auth')->group(function () {
    // dashboard utama 
    Route::get('/', function () {return view('dashboard');})->name('dashboard');
    // karyawan & gaji
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('gaji', GajiController::class);
});

// routes/web.php 
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
