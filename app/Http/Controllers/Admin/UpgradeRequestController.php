<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PlanActivatedMail;
use App\Models\UpgradeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UpgradeRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (in_array($request->route()->getActionMethod(), ['approve', 'reject']) && auth()->user()->role === 'moderator') {
                abort(403, 'Moderators cannot approve/reject upgrade requests.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    public function index()
    {
        $requests = UpgradeRequest::with(['user', 'plan'])->latest()->paginate(15);
        return view('admin.upgrade_requests.index', compact('requests'));
    }

    public function show(UpgradeRequest $upgradeRequest)
    {
        $upgradeRequest->load(['user', 'plan']);
        return view('admin.upgrade_requests.show', compact('upgradeRequest'));
    }

    public function approve(UpgradeRequest $upgradeRequest)
    {
        $upgradeRequest->update(['status' => 'approved']);

        $plan = $upgradeRequest->plan;
        $expiresAt = $plan->duration_days ? now()->addDays($plan->duration_days) : null;

        $upgradeRequest->user->userPlans()->create([
            'plan_id' => $plan->id,
            'starts_at' => now(),
            'expires_at' => $expiresAt,
        ]);

        try {
            Mail::to($upgradeRequest->user)->send(new PlanActivatedMail($upgradeRequest));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to send plan activation email: ' . $e->getMessage());
        }

        return redirect()->route('admin.upgrade-requests.index')
            ->with('success', "Request approved. User upgraded to {$plan->name}.");
    }

    public function reject(Request $request, UpgradeRequest $upgradeRequest)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $upgradeRequest->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        return redirect()->route('admin.upgrade-requests.index')
            ->with('success', 'Request rejected.');
    }
}
