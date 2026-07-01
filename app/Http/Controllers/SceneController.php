<?php

namespace App\Http\Controllers;

use App\Models\Scene;
use Illuminate\Http\Request;

class SceneController extends Controller
{
    public function save(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
            'data' => 'required|array',
            'data.objects' => 'present|array',
            'data.objects.*.file_ids' => 'required|array',
            'data.objects.*.file_ids.*' => 'integer|exists:file_types,id',
            'data.objects.*.position' => 'required|array',
            'data.objects.*.scale' => 'required|array',
            'data.objects.*.rotation' => 'required|array',
            'data.objects.*.color' => 'required|string',
            'data.objects.*.roughness' => 'nullable|numeric',
            'data.objects.*.metalness' => 'nullable|numeric',
            'data.objects.*.style' => 'nullable|string',
            'data.objects.*.hidden' => 'nullable|boolean',
            'data.save_name' => 'nullable|string|max:255',
            'data.modelColors' => 'nullable|array',
            'data.modelDeleted' => 'nullable|array',
            'data.modelDeleted.*' => 'string',
            'data.modelHidden' => 'nullable|array',
            'data.modelHidden.*' => 'string',
            'data.objects.*.meshColors' => 'nullable|array',
            'data.objects.*.textureId' => 'nullable|integer|exists:textures,id',
            'data.objects.*.texturePath' => 'nullable|string',
            'data.objects.*.textureRepeat' => 'nullable|array',
            'data.objects.*.textureRepeat.x' => 'required_with:data.objects.*.textureRepeat|numeric',
            'data.objects.*.textureRepeat.y' => 'required_with:data.objects.*.textureRepeat|numeric',
            'data.modelTextures' => 'nullable|array',
            'data.modelTextureRepeats' => 'nullable|array',
            'data.modelTexturePath' => 'nullable|string',
        ]);

        $user = $request->user();

        $scene = Scene::updateOrCreate(
            ['user_id' => $user->id, 'session_id' => $validated['session_id']],
            ['data' => $validated['data']]
        );

        return response()->json(['message' => 'Scene saved', 'scene' => $scene]);
    }

    public function load(Request $request, string $sessionId)
    {
        $user = $request->user();

        $scene = Scene::where('user_id', $user->id)
            ->where('session_id', $sessionId)
            ->first();

        if (!$scene) {
            return response()->json(['message' => 'No saved scene'], 404);
        }

        $objects = collect($scene->data['objects'] ?? [])->map(function ($obj) use ($user) {
            $files = \App\Models\FileType::whereIn('id', $obj['file_ids'])
                ->where('user_id', $user->id)
                ->get()
                ->map(fn($f) => [
                    'id' => $f->id,
                    'url' => '/storage/' . $f->file_path,
                    'original_name' => $f->original_name,
                ]);
            $entry = [
                'file_ids' => $obj['file_ids'],
                'files' => $files,
                'position' => $obj['position'],
                'scale' => $obj['scale'],
                'rotation' => $obj['rotation'],
                'color' => $obj['color'] ?? '#8b5cf6',
                'roughness' => $obj['roughness'] ?? 0.7,
                'metalness' => $obj['metalness'] ?? 0.1,
                'style' => $obj['style'] ?? 'solid',
            ];
            if (isset($obj['meshColors'])) $entry['meshColors'] = $obj['meshColors'];
            if (isset($obj['textureId'])) $entry['textureId'] = $obj['textureId'];
            if (isset($obj['texturePath'])) $entry['texturePath'] = $obj['texturePath'];
            if (isset($obj['textureRepeat'])) $entry['textureRepeat'] = $obj['textureRepeat'];
            if (isset($obj['hidden'])) $entry['hidden'] = $obj['hidden'];
            return $entry;
        });

        return response()->json([
            'objects' => $objects,
            'save_name' => $scene->data['save_name'] ?? null,
            'modelColors' => $scene->data['modelColors'] ?? null,
            'modelDeleted' => $scene->data['modelDeleted'] ?? null,
            'modelHidden' => $scene->data['modelHidden'] ?? null,
            'modelTextures' => $scene->data['modelTextures'] ?? null,
            'modelTextureRepeats' => $scene->data['modelTextureRepeats'] ?? null,
            'modelTexturePath' => $scene->data['modelTexturePath'] ?? null,
        ]);
    }
}
