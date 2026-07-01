<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialDiscount;
use Illuminate\Http\Request;

class SocialDiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy']) && auth()->user()->role === 'moderator') {
                abort(403, 'Moderators cannot modify social discounts.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    public function index()
    {
        $discounts = SocialDiscount::orderBy('sort_order')->paginate(15);
        return view('admin.social_discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('admin.social_discounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'discount_percent' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string|max:500',
            'share_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        SocialDiscount::create([
            'platform' => $validated['platform'],
            'label' => $validated['label'],
            'icon' => $validated['icon'],
            'discount_percent' => $validated['discount_percent'],
            'description' => $validated['description'],
            'share_url' => $validated['share_url'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.social-discounts.index')
            ->with('success', "Discount '{$validated['label']}' created successfully.");
    }

    public function show(SocialDiscount $socialDiscount)
    {
        return view('admin.social_discounts.show', compact('socialDiscount'));
    }

    public function edit(SocialDiscount $socialDiscount)
    {
        return view('admin.social_discounts.edit', compact('socialDiscount'));
    }

    public function update(Request $request, SocialDiscount $socialDiscount)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'discount_percent' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string|max:500',
            'share_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $socialDiscount->update([
            'platform' => $validated['platform'],
            'label' => $validated['label'],
            'icon' => $validated['icon'],
            'discount_percent' => $validated['discount_percent'],
            'description' => $validated['description'],
            'share_url' => $validated['share_url'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.social-discounts.index')
            ->with('success', "Discount '{$validated['label']}' updated successfully.");
    }

    public function destroy(SocialDiscount $socialDiscount)
    {
        $socialDiscount->delete();

        return redirect()->route('admin.social-discounts.index')
            ->with('success', 'Social discount deleted successfully.');
    }
}
