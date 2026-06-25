<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StartupIdea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IdeaApprovalController extends Controller
{
    public function index(): View
    {
        $ideas = StartupIdea::with('founder')
            ->latest()
            ->paginate(10);

        return view('admin.ideas.index', compact('ideas'));
    }

    public function show(StartupIdea $idea): View
    {
        $idea->load('founder', 'team.members.user');

        return view('admin.ideas.show', compact('idea'));
    }

    public function approve(StartupIdea $idea): RedirectResponse
    {
        $idea->update([
            'status'           => 'approved',
            'rejection_reason' => null,
        ]);

        return back()->with('success', "\"{$idea->title}\" has been approved.");
    }

    public function reject(Request $request, StartupIdea $idea): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10'],
        ]);

        $idea->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', "\"{$idea->title}\" has been rejected.");
    }
}