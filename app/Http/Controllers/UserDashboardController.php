<?php

namespace App\Http\Controllers;

use App\Mail\NewConversationMail;
use App\Models\Conversation;
use App\Models\FileType;
use App\Models\Message;
use App\Models\Plan;
use App\Models\UpgradeRequest;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $plan = $user->getCurrentPlan() ?: Plan::where('slug', 'free')->first();
        $lastUp = $user->userPlans()->latest()->first();
        $isExpired = $lastUp && $lastUp->expires_at && $lastUp->expires_at->isPast();

        $sessions = FileType::where('user_id', $user->id)
            ->whereNotNull('session_id')
            ->selectRaw('session_id, MAX(created_at) as last_upload')
            ->groupBy('session_id')
            ->orderByDesc('last_upload')
            ->get();

        $files = FileType::where('user_id', $user->id)
            ->latest()
            ->take(50)
            ->get();

        $planHistory = $user->userPlans()->with('plan')->latest()->get();

        $upgradeRequests = UpgradeRequest::where('user_id', $user->id)
            ->with('plan')
            ->latest()
            ->get();

        $plans = Plan::where('active', true)->where('slug', '!=', 'free')->orderBy('sort_order')->get();

        $conversations = Conversation::where('user_id', $user->id)
            ->with('latestMessage')
            ->latest()
            ->get();

        return view('user.dashboard', compact(
            'user', 'plan', 'isExpired', 'sessions', 'files',
            'planHistory', 'upgradeRequests', 'plans', 'conversations'
        ));
    }

    public function storeConversation(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $conversation = Conversation::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'status' => 'open',
        ]);

        $data = [
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'sender_type' => 'user',
            'message' => $validated['message'],
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('conversations', 'public');
        }

        Message::create($data);

        try {
            $admins = User::whereIn('role', ['admin', 'moderator'])->get();
            Mail::to($admins)->send(new NewConversationMail($conversation));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to send new conversation email: ' . $e->getMessage());
        }

        return redirect()->route('user.dashboard', ['tab' => 'conversations'])
            ->with('success', 'Question sent. Admin will reply shortly.');
    }

    public function storeMessage(Request $request, Conversation $conversation)
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = [
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'sender_type' => 'user',
            'message' => $validated['message'],
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('conversations', 'public');
        }

        Message::create($data);

        if ($conversation->status === 'closed') {
            $conversation->update(['status' => 'open']);
        }

        return redirect()->route('user.dashboard', ['tab' => 'conversations'])
            ->with('success', 'Message sent.');
    }
}
