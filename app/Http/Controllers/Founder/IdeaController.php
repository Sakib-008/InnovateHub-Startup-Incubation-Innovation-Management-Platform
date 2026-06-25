<?php

namespace App\Http\Controllers\Founder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Founder\StoreIdeaRequest;
use App\Http\Requests\Founder\UpdateIdeaRequest;
use App\Models\StartupIdea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class IdeaController extends Controller
{
    public function index(): View
    {
        $ideas = StartupIdea::where('founder_id', auth()->id())
            ->latest()
            ->paginate(6);

        return view('founder.ideas.index', compact('ideas'));
    }

    public function create(): View
    {
        return view('founder.ideas.create');
    }

    public function store(StoreIdeaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['founder_id'] = auth()->id();

        if ($request->hasFile('pitch_file')) {
            $data['pitch_file'] = $request->file('pitch_file')
                ->store('pitch-files', 'public');
        }

        StartupIdea::create($data);

        return redirect()->route('founder.ideas.index')
            ->with('success', 'Startup idea submitted successfully. Awaiting admin approval.');
    }

    public function show(StartupIdea $idea): View
    {
        $this->authorizeIdea($idea);
        $idea->load('team.members.user');

        return view('founder.ideas.show', compact('idea'));
    }

    public function edit(StartupIdea $idea): View
    {
        $this->authorizeIdea($idea);
        abort_if($idea->isApproved(), 403, 'Approved ideas cannot be edited.');

        return view('founder.ideas.edit', compact('idea'));
    }

    public function update(UpdateIdeaRequest $request, StartupIdea $idea): RedirectResponse
    {
        abort_if($idea->isApproved(), 403, 'Approved ideas cannot be edited.');

        $data = $request->validated();

        if ($request->hasFile('pitch_file')) {
            if ($idea->pitch_file) {
                Storage::disk('public')->delete($idea->pitch_file);
            }
            $data['pitch_file'] = $request->file('pitch_file')
                ->store('pitch-files', 'public');
        }

        // Reset to pending if edited after rejection
        if ($idea->isRejected()) {
            $data['status'] = 'pending';
            $data['rejection_reason'] = null;
        }

        $idea->update($data);

        return redirect()->route('founder.ideas.index')
            ->with('success', 'Startup idea updated successfully.');
    }

    public function destroy(StartupIdea $idea): RedirectResponse
    {
        $this->authorizeIdea($idea);

        if ($idea->pitch_file) {
            Storage::disk('public')->delete($idea->pitch_file);
        }

        $idea->delete();

        return redirect()->route('founder.ideas.index')
            ->with('success', 'Startup idea deleted.');
    }

    private function authorizeIdea(StartupIdea $idea): void
    {
        abort_if($idea->founder_id !== auth()->id(), 403);
    }
}