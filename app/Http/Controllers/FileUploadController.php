<?php

namespace App\Http\Controllers;

use App\Models\FileType;
use App\Models\Manipulation;
use App\Models\Scene;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:102400',
            'model_name' => 'nullable|string|max:255',
        ]);

        foreach ($request->file('files') as $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['obj', 'mtl', 'png'])) {
                return response()->json(['message' => 'Invalid file type: ' . $file->getClientOriginalName()], 422);
            }
        }

        $user = $request->user();
        $sessionId = $request->input('session_id', (string) Str::uuid());
        $records = [];

        foreach ($request->file('files') as $file) {
            $ext = $file->getClientOriginalExtension();
            $filename = uniqid() . '_' . time() . '.' . $ext;
            $path = $file->storeAs('uploads/' . $user->id, $filename, 'public');

            $records[] = FileType::create([
                'user_id' => $user->id,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'model_name' => $request->input('model_name'),
                'session_id' => $sessionId,
            ]);
        }

        return response()->json([
            'message' => 'Files uploaded successfully',
            'session_id' => $sessionId,
            'records' => $records,
        ], 201);
    }

    public function sessions(Request $request)
    {
        $user = $request->user();

        $sessions = FileType::where('user_id', $user->id)
            ->whereNotNull('session_id')
            ->selectRaw('session_id, MAX(created_at) as last_upload, GROUP_CONCAT(original_name SEPARATOR \', \') as files')
            ->addSelect([
                'save_name' => Scene::selectRaw('data->>"$.save_name"')
                    ->whereColumn('session_id', 'file_types.session_id')
                    ->where('user_id', $user->id)
                    ->limit(1)
            ])
            ->groupBy('session_id')
            ->orderByDesc('last_upload')
            ->get();

        return response()->json($sessions);
    }

    public function sessionFiles(Request $request, string $sessionId)
    {
        $user = $request->user();

        $files = FileType::where('user_id', $user->id)
            ->where('session_id', $sessionId)
            ->get();

        if ($files->isEmpty()) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $files = $files->map(function ($f) {
            $f->url = '/storage/' . $f->file_path;
            return $f;
        });

        return response()->json($files);
    }

    public function manipulations(Request $request)
    {
        $user = $request->user();

        $manipulations = Manipulation::where(function ($q) use ($user) {
            $q->whereNull('user_id')->orWhere('user_id', $user->id);
        })->latest()->get();

        return response()->json($manipulations);
    }
}
