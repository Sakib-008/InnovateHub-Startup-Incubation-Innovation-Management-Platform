<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $roleCounts = [
            'all'      => User::count(),
            'founder'  => User::where('role', 'founder')->count(),
            'mentor'   => User::where('role', 'mentor')->count(),
            'investor' => User::where('role', 'investor')->count(),
            'admin'    => User::where('role', 'admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'roleCounts'));
    }

    public function show(User $user): View
    {
        $user->load('investorProfile');

        $ideaCount        = $user->startupIdeas()->count();
        $mentorshipCount  = $user->mentorshipRequestsAsMentor()->where('status', 'accepted')->count();
        $interestCount    = $user->investmentInterests()->count();
        $eventCount       = $user->eventRegistrations()->count();

        return view('admin.users.show', compact(
            'user', 'ideaCount', 'mentorshipCount', 'interestCount', 'eventCount'
        ));
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 403, 'You cannot deactivate your own account.');

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "{$user->name} has been {$status}.");
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 403, 'You cannot change your own role.');

        $request->validate([
            'role' => ['required', 'in:founder,mentor,investor,admin'],
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', "{$user->name}'s role updated to {$request->role}.");
    }
}