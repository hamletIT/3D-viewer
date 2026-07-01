<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instruction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy']) && auth()->user()->role === 'moderator') {
                abort(403, 'Moderators cannot modify instructions.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    public function index()
    {
        $instructions = Instruction::orderBy('sort_order')->paginate(15);
        return view('admin.instructions.index', compact('instructions'));
    }

    public function create()
    {
        return view('admin.instructions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'active' => $request->boolean('active', true),
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('instructions', 'public');
        }

        Instruction::create($data);

        return redirect()->route('admin.instructions.index')
            ->with('success', "Instruction '{$validated['title']}' created successfully.");
    }

    public function show(Instruction $instruction)
    {
        return view('admin.instructions.show', compact('instruction'));
    }

    public function edit(Instruction $instruction)
    {
        return view('admin.instructions.edit', compact('instruction'));
    }

    public function update(Request $request, Instruction $instruction)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'active' => $request->boolean('active', true),
        ];

        if ($request->hasFile('image')) {
            if ($instruction->image_path) {
                Storage::disk('public')->delete($instruction->image_path);
            }
            $data['image_path'] = $request->file('image')->store('instructions', 'public');
        }

        $instruction->update($data);

        return redirect()->route('admin.instructions.index')
            ->with('success', "Instruction '{$validated['title']}' updated successfully.");
    }

    public function destroy(Instruction $instruction)
    {
        if ($instruction->image_path) {
            Storage::disk('public')->delete($instruction->image_path);
        }
        $instruction->delete();

        return redirect()->route('admin.instructions.index')
            ->with('success', "Instruction deleted successfully.");
    }
}
