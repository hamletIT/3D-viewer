<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy']) && auth()->user()->role === 'moderator') {
                abort(403, 'Moderators cannot modify plans.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    public function index()
    {
        $plans = Plan::orderBy('sort_order')->paginate(15);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug',
            'max_sessions' => 'required|integer|min:-1',
            'max_objects_per_scene' => 'required|integer|min:-1',
            'price' => 'required|numeric|min:0',
            'icon' => 'required|string|max:50',
            'duration_days' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);

        Plan::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'max_sessions' => $validated['max_sessions'],
            'max_objects_per_scene' => $validated['max_objects_per_scene'],
            'price' => $validated['price'],
            'icon' => $validated['icon'],
            'duration_days' => $validated['duration_days'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'active' => $request->boolean('active', true),
        ]);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan '{$validated['name']}' created successfully.");
    }

    public function show(Plan $plan)
    {
        return view('admin.plans.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $plan->id,
            'max_sessions' => 'required|integer|min:-1',
            'max_objects_per_scene' => 'required|integer|min:-1',
            'price' => 'required|numeric|min:0',
            'icon' => 'required|string|max:50',
            'duration_days' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);

        $plan->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'max_sessions' => $validated['max_sessions'],
            'max_objects_per_scene' => $validated['max_objects_per_scene'],
            'price' => $validated['price'],
            'icon' => $validated['icon'],
            'duration_days' => $validated['duration_days'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'active' => $request->boolean('active', true),
        ]);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan '{$validated['name']}' updated successfully.");
    }

    public function destroy(Plan $plan)
    {
        if ($plan->slug === 'free') {
            return redirect()->route('admin.plans.index')
                ->with('error', 'Cannot delete the Free plan.');
        }
        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan deleted successfully.");
    }
}
