<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy']) && auth()->user()->role === 'moderator') {
                abort(403, 'Moderators cannot modify settings.');
            }
            return $next($request);
        })->except(['index']);
    }

    public function index()
    {
        $settings = Setting::latest()->paginate(10);
        return view('admin.settings.index', compact('settings'));
    }

    public function create()
    {
        return view('admin.settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:settings', 'regex:/^[a-zA-Z][a-zA-Z0-9_.]*$/'],
            'value' => ['nullable', 'string'],
            'type' => ['required', 'string', Rule::in(['string', 'text', 'boolean', 'integer', 'json'])],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Setting::create($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', "Setting '{$validated['key']}' created successfully.");
    }

    public function edit(Setting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', Rule::unique('settings')->ignore($setting), 'regex:/^[a-zA-Z][a-zA-Z0-9_.]*$/'],
            'value' => ['nullable', 'string'],
            'type' => ['required', 'string', Rule::in(['string', 'text', 'boolean', 'integer', 'json'])],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $setting->update($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', "Setting '{$validated['key']}' updated successfully.");
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();

        return redirect()->route('admin.settings.index')
            ->with('success', "Setting '{$setting->key}' deleted successfully.");
    }
}
