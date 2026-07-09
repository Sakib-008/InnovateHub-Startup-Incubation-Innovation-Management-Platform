<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Investor\StoreInvestmentInterestRequest;
use App\Models\InvestmentInterest;
use App\Models\StartupIdea;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InvestmentInterestController extends Controller
{
    public function index(): View
    {
        $interests = auth()->user()->investmentInterests()
            ->with('startupIdea.founder', 'startupIdea.showcase')
            ->latest()
            ->paginate(10);

        return view('investor.interests', compact('interests'));
    }

    public function store(StoreInvestmentInterestRequest $request, StartupIdea $idea): RedirectResponse
    {
        abort_if(! $idea->isApproved(), 403);

        $exists = InvestmentInterest::where('investor_id', auth()->id())
            ->where('startup_idea_id', $idea->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['interest' => 'You have already expressed interest in this startup.']);
        }

        InvestmentInterest::create([
            'investor_id'      => auth()->id(),
            'startup_idea_id'  => $idea->id,
            'message'          => $request->message,
        ]);

        return back()->with('success', 'Interest expressed! The founder will be notified.');
    }

    public function destroy(InvestmentInterest $interest): RedirectResponse
    {
        abort_if($interest->investor_id !== auth()->id(), 403);
        $interest->delete();

        return back()->with('success', 'Interest withdrawn.');
    }
}