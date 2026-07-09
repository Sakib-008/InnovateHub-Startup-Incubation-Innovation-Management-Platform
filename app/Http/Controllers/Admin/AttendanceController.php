<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\RedirectResponse;

class AttendanceController extends Controller
{
    public function toggle(Event $event, EventRegistration $registration): RedirectResponse
    {
        $registration->update(['attended' => ! $registration->attended]);

        return back()->with('success', 'Attendance updated.');
    }
}