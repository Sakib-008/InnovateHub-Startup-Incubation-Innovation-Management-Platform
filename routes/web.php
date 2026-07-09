<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Founder\IdeaController;
use App\Http\Controllers\Founder\TeamController;
use App\Http\Controllers\Founder\MentorshipController as FounderMentorshipController;
use App\Http\Controllers\Founder\MilestoneController;
use App\Http\Controllers\Founder\TaskController;
use App\Http\Controllers\Founder\ShowcaseController;
use App\Http\Controllers\Mentor\MentorshipController as MentorMentorshipController;
use App\Http\Controllers\Investor\StartupBrowserController;
use App\Http\Controllers\Investor\InvestmentInterestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\IdeaApprovalController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'))->name('home');

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

    // Messaging — all roles
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessagingController::class, 'index'])->name('index');
        Route::get('/start/{user}', [MessagingController::class, 'startOrShow'])->name('start');
        Route::get('/{conversation}', [MessagingController::class, 'show'])->name('show');
        Route::post('/{conversation}/send', [MessagingController::class, 'send'])->name('send');
    });

    // Events — all roles
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/{event}', [EventController::class, 'show'])->name('show');
        Route::post('/{event}/register', [EventController::class, 'register'])->name('register');
        Route::delete('/{event}/unregister', [EventController::class, 'unregister'])->name('unregister');
    });
});

// ---- Founder ----
Route::middleware(['auth', 'role:founder'])
    ->prefix('founder')->name('founder.')
    ->group(function () {

    Route::get('/dashboard', fn() => view('founder.dashboard'))->name('dashboard');
    Route::resource('ideas', IdeaController::class);

    Route::get('ideas/{idea}/team/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('ideas/{idea}/team', [TeamController::class, 'store'])->name('teams.store');
    Route::get('ideas/{idea}/team', [TeamController::class, 'show'])->name('teams.show');
    Route::post('ideas/{idea}/team/members', [TeamController::class, 'addMember'])->name('teams.members.add');
    Route::delete('ideas/{idea}/team/members/{member}', [TeamController::class, 'removeMember'])->name('teams.members.remove');

    Route::get('ideas/{idea}/mentorship', [FounderMentorshipController::class, 'index'])->name('mentorship.index');
    Route::post('ideas/{idea}/mentorship', [FounderMentorshipController::class, 'store'])->name('mentorship.store');

    Route::get('ideas/{idea}/milestones', [MilestoneController::class, 'index'])->name('milestones.index');
    Route::post('ideas/{idea}/milestones', [MilestoneController::class, 'store'])->name('milestones.store');
    Route::delete('ideas/{idea}/milestones/{milestone}', [MilestoneController::class, 'destroy'])->name('milestones.destroy');

    Route::post('ideas/{idea}/milestones/{milestone}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('ideas/{idea}/milestones/{milestone}/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('ideas/{idea}/milestones/{milestone}/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::get('ideas/{idea}/showcase', [ShowcaseController::class, 'edit'])->name('showcase.edit');
    Route::post('ideas/{idea}/showcase', [ShowcaseController::class, 'update'])->name('showcase.update');
    Route::delete('ideas/{idea}/showcase/images/{index}', [ShowcaseController::class, 'deleteImage'])->name('showcase.image.delete');
    Route::patch('ideas/{idea}/interests/{interest}/status', [ShowcaseController::class, 'updateInterestStatus'])->name('interests.status');
});

// ---- Mentor ----
Route::middleware(['auth', 'role:mentor'])
    ->prefix('mentor')->name('mentor.')
    ->group(function () {

    Route::get('/dashboard', fn() => view('mentor.dashboard'))->name('dashboard');
    Route::get('/requests', [MentorMentorshipController::class, 'index'])->name('requests.index');
    Route::patch('/requests/{mentorshipRequest}/accept', [MentorMentorshipController::class, 'accept'])->name('requests.accept');
    Route::patch('/requests/{mentorshipRequest}/reject', [MentorMentorshipController::class, 'reject'])->name('requests.reject');
    Route::get('/startups', [MentorMentorshipController::class, 'assignedStartups'])->name('startups.index');
});

// ---- Investor ----
Route::middleware(['auth', 'role:investor'])
    ->prefix('investor')->name('investor.')
    ->group(function () {

    Route::get('/dashboard', fn() => view('investor.dashboard'))->name('dashboard');
    Route::get('/browse', [StartupBrowserController::class, 'index'])->name('browse');
    Route::get('/browse/{idea}', [StartupBrowserController::class, 'show'])->name('startup.show');
    Route::post('/browse/{idea}/interest', [InvestmentInterestController::class, 'store'])->name('interest.store');
    Route::get('/interests', [InvestmentInterestController::class, 'index'])->name('interests.index');
    Route::delete('/interests/{interest}', [InvestmentInterestController::class, 'destroy'])->name('interest.destroy');
});

// ---- Admin ----
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User management
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');

    // Ideas
    Route::get('ideas', [IdeaApprovalController::class, 'index'])->name('ideas.index');
    Route::get('ideas/{idea}', [IdeaApprovalController::class, 'show'])->name('ideas.show');
    Route::patch('ideas/{idea}/approve', [IdeaApprovalController::class, 'approve'])->name('ideas.approve');
    Route::patch('ideas/{idea}/reject', [IdeaApprovalController::class, 'reject'])->name('ideas.reject');

    // Events
    Route::resource('events', AdminEventController::class);
    Route::patch('events/{event}/attendance/{registration}', [AttendanceController::class, 'toggle'])
        ->name('events.attendance.toggle');
});

require __DIR__.'/auth.php';