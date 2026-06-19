<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'mentor'   => redirect()->route('mentor.dashboard'),
            'investor' => redirect()->route('investor.dashboard'),
            default    => redirect()->route('founder.dashboard'),
        };
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Founder routes
Route::middleware(['auth', 'role:founder'])->prefix('founder')->name('founder.')->group(function () {
    Route::get('/dashboard', function () {
        return view('founder.dashboard');
    })->name('dashboard');
});

// Mentor routes
Route::middleware(['auth', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('mentor.dashboard');
    })->name('dashboard');
});

// Investor routes
Route::middleware(['auth', 'role:investor'])->prefix('investor')->name('investor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('investor.dashboard');
    })->name('dashboard');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';