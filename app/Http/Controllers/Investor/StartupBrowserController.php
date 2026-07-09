<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\StartupIdea;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StartupBrowserController extends Controller
{
    public function index(Request $request): View
    {
        $query = StartupIdea::approved()
            ->has('showcase')
            ->with(['founder', 'showcase', 'investmentInterests'])
            ->withCount('investmentInterests');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $startups = $query->paginate(9)->withQueryString();

        $categories = StartupIdea::approved()->has('showcase')
            ->distinct()->pluck('category');

        // Track which ideas this investor has already expressed interest in
        $interestedIds = auth()->user()->investmentInterests()
            ->pluck('startup_idea_id')->toArray();

        return view('investor.browse', compact('startups', 'categories', 'interestedIds'));
    }

    public function show(StartupIdea $idea): View
    {
        abort_if(! $idea->isApproved(), 404);
        abort_if(! $idea->showcase?->is_public, 404);

        $idea->load('founder', 'showcase', 'team.members.user');

        $hasInterest = auth()->user()->investmentInterests()
            ->where('startup_idea_id', $idea->id)
            ->exists();

        return view('investor.startup-detail', compact('idea', 'hasInterest'));
    }
}