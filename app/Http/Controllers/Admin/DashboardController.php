<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\InvestmentInterest;
use App\Models\MentorshipRequest;
use App\Models\StartupIdea;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users'       => User::count(),
            'total_founders'    => User::where('role', 'founder')->count(),
            'total_mentors'     => User::where('role', 'mentor')->count(),
            'total_investors'   => User::where('role', 'investor')->count(),
            'total_ideas'       => StartupIdea::count(),
            'pending_ideas'     => StartupIdea::pending()->count(),
            'approved_ideas'    => StartupIdea::approved()->count(),
            'total_events'      => Event::count(),
            'upcoming_events'   => Event::upcoming()->count(),
            'total_mentorships' => MentorshipRequest::where('status', 'accepted')->count(),
            'total_interests'   => InvestmentInterest::count(),
        ];

        $recentIdeas = StartupIdea::with('founder')
            ->latest()->limit(5)->get();

        $recentUsers = User::latest()->limit(5)->get();

        $recentEvents = Event::upcoming()->limit(3)->get();

        return view('admin.dashboard', compact('stats', 'recentIdeas', 'recentUsers', 'recentEvents'));
    }
}