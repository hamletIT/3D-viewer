<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingFeature;
use App\Models\LandingSection;
use Illuminate\Http\Request;

class LandingSectionController extends Controller
{
    public function index()
    {
        $sections = LandingSection::orderBy('sort_order')->paginate(20);
        return view('admin.landing-sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.landing-sections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:landing_sections,slug',
            'type' => 'required|string|max:50',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link_url' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:255',
            'data' => 'nullable|json',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);

        $data = [
            'slug' => $validated['slug'],
            'type' => $validated['type'],
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'content' => $validated['content'],
            'icon' => $validated['icon'],
            'link_url' => $validated['link_url'],
            'link_text' => $validated['link_text'],
            'data' => $validated['data'] ? json_decode($validated['data'], true) : null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'active' => $request->boolean('active', true),
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('landing', 'public');
        }

        LandingSection::create($data);

        return redirect()->route('admin.landing-sections.index')
            ->with('success', "Section '{$validated['slug']}' created.");
    }

    public function show(LandingSection $landingSection)
    {
        $landingSection->load('features');
        return view('admin.landing-sections.show', compact('landingSection'));
    }

    public function edit(LandingSection $landingSection)
    {
        $landingSection->load('features');
        return view('admin.landing-sections.edit', compact('landingSection'));
    }

    public function update(Request $request, LandingSection $landingSection)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:landing_sections,slug,' . $landingSection->id,
            'type' => 'required|string|max:50',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link_url' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:255',
            'data' => 'nullable|json',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);

        $data = [
            'slug' => $validated['slug'],
            'type' => $validated['type'],
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'content' => $validated['content'],
            'icon' => $validated['icon'],
            'link_url' => $validated['link_url'],
            'link_text' => $validated['link_text'],
            'data' => $validated['data'] ? json_decode($validated['data'], true) : null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'active' => $request->boolean('active', true),
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('landing', 'public');
        }

        $landingSection->update($data);

        return redirect()->route('admin.landing-sections.index')
            ->with('success', "Section '{$validated['slug']}' updated.");
    }

    public function destroy(LandingSection $landingSection)
    {
        $landingSection->delete();
        return redirect()->route('admin.landing-sections.index')
            ->with('success', 'Section deleted.');
    }

    public function storeFeature(Request $request, LandingSection $landingSection)
    {
        $validated = $request->validate([
            'icon' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        LandingFeature::create([
            'section_id' => $landingSection->id,
            'icon' => $validated['icon'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'active' => true,
        ]);

        return redirect()->route('admin.landing-sections.edit', $landingSection)
            ->with('success', 'Feature added.');
    }

    public function updateFeature(Request $request, LandingFeature $landingFeature)
    {
        $validated = $request->validate([
            'icon' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);

        $landingFeature->update($validated);

        return redirect()->route('admin.landing-sections.edit', $landingFeature->section)
            ->with('success', 'Feature updated.');
    }

    public function destroyFeature(LandingFeature $landingFeature)
    {
        $section = $landingFeature->section;
        $landingFeature->delete();
        return redirect()->route('admin.landing-sections.edit', $section)
            ->with('success', 'Feature deleted.');
    }
}
