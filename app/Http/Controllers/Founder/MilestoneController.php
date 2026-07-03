<?php

namespace App\Http\Controllers\Founder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Founder\StoreMilestoneRequest;
use App\Models\Milestone;
use App\Models\StartupIdea;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MilestoneController extends Controller
{
    public function index(StartupIdea $idea): View
    {
        abort_if($idea->founder_id !== auth()->id(), 403);

        $milestones = $idea->milestones()->with('tasks.assignee')->get();

        return view('founder.milestones.index', compact('idea', 'milestones'));
    }

    public function store(StoreMilestoneRequest $request, StartupIdea $idea): RedirectResponse
    {
        abort_if($idea->founder_id !== auth()->id(), 403);
        abort_if(! $idea->isApproved(), 403, 'Only approved ideas can track progress.');

        $idea->milestones()->create($request->validated());

        return back()->with('success', 'Milestone added.');
    }

    public function destroy(StartupIdea $idea, Milestone $milestone): RedirectResponse
    {
        abort_if($idea->founder_id !== auth()->id(), 403);

        $milestone->delete();

        return back()->with('success', 'Milestone deleted.');
    }
}