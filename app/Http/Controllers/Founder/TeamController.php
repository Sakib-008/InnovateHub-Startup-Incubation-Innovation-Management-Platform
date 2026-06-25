<?php

namespace App\Http\Controllers\Founder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Founder\StoreTeamRequest;
use App\Http\Requests\Founder\StoreTeamMemberRequest;
use App\Models\StartupIdea;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function create(StartupIdea $idea): View
    {
        abort_if($idea->founder_id !== auth()->id(), 403);
        abort_if($idea->team()->exists(), 403, 'This idea already has a team.');

        return view('founder.teams.create', compact('idea'));
    }

    public function store(StoreTeamRequest $request, StartupIdea $idea): RedirectResponse
    {
        abort_if($idea->founder_id !== auth()->id(), 403);

        $team = $idea->team()->create($request->validated());

        // Auto-add founder as team member
        $team->members()->create([
            'user_id'      => auth()->id(),
            'role_in_team' => 'Founder',
        ]);

        return redirect()->route('founder.ideas.show', $idea)
            ->with('success', 'Team created successfully.');
    }

    public function show(StartupIdea $idea): View
    {
        abort_if($idea->founder_id !== auth()->id(), 403);
        $team = $idea->team()->with('members.user')->firstOrFail();

        // Get all founders who are not already in the team
        $existingUserIds = $team->members->pluck('user_id')->toArray();
        $availableUsers  = User::where('role', 'founder')
            ->whereNotIn('id', $existingUserIds)
            ->get();

        return view('founder.teams.show', compact('idea', 'team', 'availableUsers'));
    }

    public function addMember(StoreTeamMemberRequest $request, StartupIdea $idea): RedirectResponse
    {
        abort_if($idea->founder_id !== auth()->id(), 403);

        $team = $idea->team()->firstOrFail();
        $user = User::where('email', $request->email)->firstOrFail();

        // Check not already a member
        if ($team->members()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['email' => 'This user is already a team member.']);
        }

        $team->members()->create([
            'user_id'      => $user->id,
            'role_in_team' => $request->role_in_team,
        ]);

        return back()->with('success', "{$user->name} added to the team.");
    }

    public function removeMember(StartupIdea $idea, TeamMember $member): RedirectResponse
    {
        abort_if($idea->founder_id !== auth()->id(), 403);
        abort_if($member->user_id === auth()->id(), 403, 'You cannot remove yourself as founder.');

        $member->delete();

        return back()->with('success', 'Team member removed.');
    }
}