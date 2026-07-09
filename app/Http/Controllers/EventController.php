<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $query = Event::withCount('registrations')->latest('event_date');

        // Filter by status using query builder
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $events = $query->paginate(9)->withQueryString();

        return view('events.index', compact('events'));
    }

    public function show(Event $event): View
    {
        $event->loadCount('registrations');
        $isRegistered = $event->isRegistered();

        return view('events.show', compact('event', 'isRegistered'));
    }

    public function register(Event $event): RedirectResponse
    {
        if ($event->isRegistered()) {
            return back()->withErrors(['event' => 'You are already registered.']);
        }

        if ($event->isFull()) {
            return back()->withErrors(['event' => 'This event is full.']);
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id'  => auth()->id(),
        ]);

        return back()->with('success', 'Successfully registered for the event!');
    }

    public function unregister(Event $event): RedirectResponse
    {
        EventRegistration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Registration cancelled.');
    }
}