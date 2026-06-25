<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Founder\IdeaController;
use App\Http\Controllers\Founder\TeamController;
use App\Http\Controllers\Admin\IdeaApprovalController;
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

// ---- Founder routes ----
Route::middleware(['auth', 'role:founder'])
    ->prefix('founder')
    ->name('founder.')
    ->group(function () {

    Route::get('/dashboard', function () {
        return view('founder.dashboard');
    })->name('dashboard');

    // Startup ideas
    Route::resource('ideas', IdeaController::class);

    // Teams (nested under idea)
    Route::get('ideas/{idea}/team/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('ideas/{idea}/team', [TeamController::class, 'store'])->name('teams.store');
    Route::get('ideas/{idea}/team', [TeamController::class, 'show'])->name('teams.show');
    Route::post('ideas/{idea}/team/members', [TeamController::class, 'addMember'])->name('teams.members.add');
    Route::delete('ideas/{idea}/team/members/{member}', [TeamController::class, 'removeMember'])->name('teams.members.remove');
});

// ---- Mentor routes ----
Route::middleware(['auth', 'role:mentor'])
    ->prefix('mentor')
    ->name('mentor.')
    ->group(function () {

    Route::get('/dashboard', function () {
        return view('mentor.dashboard');
    })->name('dashboard');
});

// ---- Investor routes ----
Route::middleware(['auth', 'role:investor'])
    ->prefix('investor')
    ->name('investor.')
    ->group(function () {

    Route::get('/dashboard', function () {
        return view('investor.dashboard');
    })->name('dashboard');
});

// ---- Admin routes ----
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Idea approval
    Route::get('ideas', [IdeaApprovalController::class, 'index'])->name('ideas.index');
    Route::get('ideas/{idea}', [IdeaApprovalController::class, 'show'])->name('ideas.show');
    Route::patch('ideas/{idea}/approve', [IdeaApprovalController::class, 'approve'])->name('ideas.approve');
    Route::patch('ideas/{idea}/reject', [IdeaApprovalController::class, 'reject'])->name('ideas.reject');
});

require __DIR__.'/auth.php';