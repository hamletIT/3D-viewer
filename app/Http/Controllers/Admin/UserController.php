<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy']) && auth()->user()->role === 'moderator') {
                abort(403, 'Moderators cannot modify users.');
            }
            return $next($request);
        })->except(['index']);
    }

    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'role' => ['required', Rule::in(['user', 'moderator', 'admin'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users/photos', 'public');
            $user->update(['photo' => $path]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' created successfully.");
    }

    public function edit(User $user)
    {
        $plans = Plan::orderBy('sort_order')->get();
        $currentPlan = $user->getCurrentPlan();
        return view('admin.users.edit', compact('user', 'plans', 'currentPlan'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'role' => ['required', Rule::in(['user', 'moderator', 'admin'])],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        if ($validated['password']) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('users/photos', 'public');
            $user->update(['photo' => $path]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' updated successfully.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        if ($user->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' deleted successfully.");
    }

    public function changePlan(Request $request, User $user)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);

        UserPlan::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => now(),
            'expires_at' => $plan->duration_days ? now()->addDays($plan->duration_days) : null,
        ]);

        return redirect()->route('admin.users.edit', $user)
            ->with('success', "User '{$user->name}' switched to {$plan->name} plan.");
    }

    public function removePlan(User $user)
    {
        $free = Plan::where('slug', 'free')->firstOrFail();

        UserPlan::create([
            'user_id' => $user->id,
            'plan_id' => $free->id,
            'starts_at' => now(),
            'expires_at' => null,
        ]);

        return redirect()->route('admin.users.edit', $user)
            ->with('success', "User '{$user->name}' reset to Free plan.");
    }
}
