<?php

namespace App\Http\Controllers;

use App\Http\Requests\Messaging\SendMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessagingController extends Controller
{
    // Inbox — list all conversations
    public function index(): View
    {
        $conversations = auth()->user()->conversations();

        return view('messaging.index', compact('conversations'));
    }

    // Show a conversation thread
    public function show(Conversation $conversation): View
    {
        $this->authorizeConversation($conversation);

        // Mark incoming messages as read
        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()->with('sender')->get();
        $other    = $conversation->otherUser();

        return view('messaging.show', compact('conversation', 'messages', 'other'));
    }

    // Start or resume a conversation with a user
    public function startOrShow(User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 403, 'Cannot message yourself.');

        $conversation = Conversation::between(auth()->id(), $user->id);

        return redirect()->route('messages.show', $conversation);
    }

    // Send a message
    public function send(SendMessageRequest $request, Conversation $conversation): RedirectResponse
    {
        $this->authorizeConversation($conversation);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => auth()->id(),
            'body'            => $request->body,
        ]);

        return back();
    }

    private function authorizeConversation(Conversation $conversation): void
    {
        abort_if(
            $conversation->user_one_id !== auth()->id() &&
            $conversation->user_two_id !== auth()->id(),
            403
        );
    }
}