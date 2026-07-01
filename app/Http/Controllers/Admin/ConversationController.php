<?php

namespace App\Http\Controllers\Admin;

use App\Mail\AdminReplyMail;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Conversation::with(['user', 'latestMessage'])
            ->latest()
            ->paginate(20);
        return view('admin.conversations.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $conversation->load(['user', 'messages.sender']);
        $unread = $conversation->messages()
            ->where('sender_type', 'user')
            ->where('created_at', '>', $conversation->admin_id ? $conversation->updated_at : now()->subYear())
            ->count();
        return view('admin.conversations.show', compact('conversation', 'unread'));
    }

    public function reply(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = [
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'sender_type' => 'admin',
            'message' => $validated['message'],
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('conversations', 'public');
        }

        Message::create($data);

        try {
            Mail::to($conversation->user)->send(new AdminReplyMail($conversation));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to send admin reply email: ' . $e->getMessage());
        }

        if (!$conversation->admin_id) {
            $conversation->update(['admin_id' => auth()->id()]);
        }

        if ($conversation->status === 'closed') {
            $conversation->update(['status' => 'open']);
        }

        return redirect()->route('admin.conversations.show', $conversation)
            ->with('success', 'Reply sent.');
    }

    public function close(Conversation $conversation)
    {
        $conversation->update(['status' => 'closed']);
        return redirect()->route('admin.conversations.index')
            ->with('success', 'Conversation closed.');
    }

    public function open(Conversation $conversation)
    {
        $conversation->update(['status' => 'open']);
        return redirect()->route('admin.conversations.show', $conversation)
            ->with('success', 'Conversation re-opened.');
    }
}
