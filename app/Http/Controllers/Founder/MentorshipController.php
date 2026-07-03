<?php

namespace App\Http\Controllers\Founder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Founder\StoreMentorshipRequest;
use App\Models\MentorshipRequest;
use App\Models\StartupIdea;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MentorshipController extends Controller
{
    // List mentors + request form per idea
    public function index(StartupIdea $idea): View
    {
        abort_if($idea->founder_id !== auth()->id(), 403);

        $mentors = User::where('role', 'mentor')->get();

        $existingRequests = MentorshipRequest::where('startup_idea_id', $idea->id)
            ->pluck('status', 'mentor_id');

        return view('founder.mentorship.index', compact('idea', 'mentors', 'existingRequests'));
    }

    public function store(StoreMentorshipRequest $request, StartupIdea $idea): RedirectResponse
    {
        abort_if($idea->founder_id !== auth()->id(), 403);
        abort_if(! $idea->isApproved(), 403, 'Only approved ideas can request mentorship.');

        // Check not already requested
        $exists = MentorshipRequest::where('startup_idea_id', $idea->id)
            ->where('mentor_id', $request->mentor_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['mentor_id' => 'You have already sent a request to this mentor.']);
        }

        MentorshipRequest::create([
            'startup_idea_id' => $idea->id,
            'founder_id'      => auth()->id(),
            'mentor_id'       => $request->mentor_id,
            'message'         => $request->message,
        ]);

        return back()->with('success', 'Mentorship request sent successfully.');
    }
}