<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($request->route()->getActionMethod() === 'destroy' && auth()->user()->role === 'moderator') {
                abort(403, 'Moderators cannot delete files.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $fileTypes = FileType::with('user')->latest()->paginate(10);
        return view('admin.file_types.index', compact('fileTypes'));
    }

    public function show(FileType $fileType)
    {
        $fileType->load('user');
        return view('admin.file_types.show', compact('fileType'));
    }

    public function destroy(FileType $fileType)
    {
        if ($fileType->file_path) {
            Storage::disk('public')->delete($fileType->file_path);
        }

        $fileType->delete();

        return redirect()->route('admin.file-types.index')
            ->with('success', "File '{$fileType->original_name}' deleted successfully.");
    }
}
