<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Texture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TextureController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy']) && auth()->user()->role === 'moderator') {
                abort(403, 'Moderators cannot modify textures.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    public function index()
    {
        $textures = Texture::latest()->paginate(15);
        return view('admin.textures.index', compact('textures'));
    }

    public function create()
    {
        return view('admin.textures.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $file = $request->file('image');
        $path = $file->store('textures', 'public');

        Texture::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
        ]);

        return redirect()->route('admin.textures.index')
            ->with('success', "Texture '{$validated['name']}' uploaded successfully.");
    }

    public function show(Texture $texture)
    {
        return view('admin.textures.show', compact('texture'));
    }

    public function edit(Texture $texture)
    {
        return view('admin.textures.edit', compact('texture'));
    }

    public function update(Request $request, Texture $texture)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($texture->file_path);
            $path = $request->file('image')->store('textures', 'public');
            $texture->file_path = $path;
            $texture->original_name = $request->file('image')->getClientOriginalName();
        }

        $texture->name = $validated['name'];
        $texture->save();

        return redirect()->route('admin.textures.index')
            ->with('success', "Texture '{$validated['name']}' updated successfully.");
    }

    public function destroy(Texture $texture)
    {
        Storage::disk('public')->delete($texture->file_path);
        $texture->delete();

        return redirect()->route('admin.textures.index')
            ->with('success', "Texture deleted successfully.");
    }
}
