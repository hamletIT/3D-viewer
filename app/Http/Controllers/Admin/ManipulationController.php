<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manipulation;
use Illuminate\Http\Request;

class ManipulationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy']) && auth()->user()->role === 'moderator') {
                abort(403, 'Moderators cannot modify manipulations.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    public function index()
    {
        $manipulations = Manipulation::latest()->paginate(15);
        return view('admin.manipulations.index', compact('manipulations'));
    }

    public function create()
    {
        return view('admin.manipulations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'color' => 'required|string|max:9',
            'scale' => 'required|numeric|min:0.01|max:999.99',
            'position_x' => 'required|numeric',
            'position_y' => 'required|numeric',
            'position_z' => 'required|numeric',
            'rotation_x' => 'required|numeric',
            'rotation_y' => 'required|numeric',
            'rotation_z' => 'required|numeric',
            'roughness' => 'required|numeric|min:0|max:1',
            'metalness' => 'required|numeric|min:0|max:1',
            'style' => 'required|string|max:20',
            'random_color' => 'sometimes|boolean',
            'colors' => 'nullable|array',
            'colors.*' => 'string|max:9',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['random_color'] = $request->boolean('random_color');
        $validated['colors'] = $request->input('colors');

        Manipulation::create($validated);

        return redirect()->route('admin.manipulations.index')
            ->with('success', "Manipulation '{$validated['model_name']}' created successfully.");
    }

    public function show(Manipulation $manipulation)
    {
        return view('admin.manipulations.show', compact('manipulation'));
    }

    public function edit(Manipulation $manipulation)
    {
        return view('admin.manipulations.edit', compact('manipulation'));
    }

    public function update(Request $request, Manipulation $manipulation)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'color' => 'required|string|max:9',
            'scale' => 'required|numeric|min:0.01|max:999.99',
            'position_x' => 'required|numeric',
            'position_y' => 'required|numeric',
            'position_z' => 'required|numeric',
            'rotation_x' => 'required|numeric',
            'rotation_y' => 'required|numeric',
            'rotation_z' => 'required|numeric',
            'roughness' => 'required|numeric|min:0|max:1',
            'metalness' => 'required|numeric|min:0|max:1',
            'style' => 'required|string|max:20',
            'random_color' => 'sometimes|boolean',
            'colors' => 'nullable|array',
            'colors.*' => 'string|max:9',
        ]);

        $validated['random_color'] = $request->boolean('random_color');
        $validated['colors'] = $request->input('colors');
        $manipulation->update($validated);

        return redirect()->route('admin.manipulations.index')
            ->with('success', "Manipulation '{$validated['model_name']}' updated successfully.");
    }

    public function destroy(Manipulation $manipulation)
    {
        $manipulation->delete();

        return redirect()->route('admin.manipulations.index')
            ->with('success', "Manipulation deleted successfully.");
    }
}
