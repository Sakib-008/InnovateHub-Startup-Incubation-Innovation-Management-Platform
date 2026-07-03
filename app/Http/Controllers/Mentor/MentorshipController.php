<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\MentorshipRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MentorshipController extends Controller
{
    public function index(): View
    {
        $requests = MentorshipRequest::where('mentor_id', auth()->id())
            ->with('founder', 'startupIdea')
            ->latest()
            ->paginate(10);

        return view('mentor.mentorship.index', compact('requests'));
    }

    public function accept(MentorshipRequest $mentorshipRequest): RedirectResponse
    {
        abort_if($mentorshipRequest->mentor_id !== auth()->id(), 403);

        $mentorshipRequest->update([
            'status'           => 'accepted',
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Mentorship request accepted.');
    }

    public function reject(Request $request, MentorshipRequest $mentorshipRequest): RedirectResponse
    {
        abort_if($mentorshipRequest->mentor_id !== auth()->id(), 403);

        $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $mentorshipRequest->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Request rejected.');
    }

    public function assignedStartups(): View
    {
        $accepted = MentorshipRequest::where('mentor_id', auth()->id())
            ->where('status', 'accepted')
            ->with('startupIdea.founder', 'startupIdea.milestones')
            ->get();

        return view('mentor.mentorship.assigned', compact('accepted'));
    }
}