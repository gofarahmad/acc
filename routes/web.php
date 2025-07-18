<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('add');
    });

    Route::post('/accounts/import', [AccountController::class, 'import'])->name('accounts.import');
});

// Tambahkan ini agar tidak error jika user belum login
Route::get('/login', fn () => redirect('/admin/login'))->name('login');
