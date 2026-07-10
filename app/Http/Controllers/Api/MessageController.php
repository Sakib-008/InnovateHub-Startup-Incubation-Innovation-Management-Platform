<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    /**
     * GET /api/messages/unread-count
     * Polled by JS every 30 seconds to update the navbar badge.
     */
    public function unreadCount(): JsonResponse
    {
        $count = auth()->user()->unreadMessagesCount();

        return response()->json([
            'success' => true,
            'count'   => $count,
        ]);
    }
}