@extends('admin.layouts.app')

@section('title', 'Create Manipulation')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.manipulations.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Manipulations</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">Create Manipulation</h1>
    </div>

    <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <form method="POST" action="{{ route('admin.manipulations.store') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div class="col-span-2">
                    <label for="model_name" class="block text-sm font-medium mb-2">Model Name</label>
                    <input type="text" id="model_name" name="model_name" value="{{ old('model_name', 'Default Model') }}" required
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('model_name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="color" class="block text-sm font-medium mb-2">Color</label>
                    <div class="flex gap-2">
                        <input type="color" id="color_picker" value="{{ old('color', '#8b5cf6') }}"
                            oninput="document.getElementById('color').value=this.value"
                            class="w-10 h-10 rounded cursor-pointer bg-transparent border-0">
                        <input type="text" id="color" name="color" value="{{ old('color', '#8b5cf6') }}" required
                            class="flex-1 rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    </div>
                    @error('color') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-6">
                    <input type="hidden" name="random_color" value="0">
                    <input type="checkbox" id="random_color" name="random_color" value="1" {{ old('random_color') ? 'checked' : '' }}
                        class="w-4 h-4 rounded bg-gray-800 border-gray-700 text-indigo-600 focus:ring-indigo-500"
                        onchange="document.getElementById('colorsModal').style.display = this.checked ? 'flex' : 'none'">
                    <label for="random_color" class="text-sm font-medium">Random Color per Mesh</label>
                </div>

                <div>
                    <label for="scale" class="block text-sm font-medium mb-2">Scale</label>
                    <input type="number" id="scale" name="scale" value="{{ old('scale', '1.00') }}" step="0.01" min="0.01"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('scale') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="roughness" class="block text-sm font-medium mb-2">Roughness</label>
                    <input type="number" id="roughness" name="roughness" value="{{ old('roughness', '0.70') }}" step="0.01" min="0" max="1"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('roughness') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="metalness" class="block text-sm font-medium mb-2">Metalness</label>
                    <input type="number" id="metalness" name="metalness" value="{{ old('metalness', '0.10') }}" step="0.01" min="0" max="1"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('metalness') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="style" class="block text-sm font-medium mb-2">Style</label>
                    <select id="style" name="style" required
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                        <option value="solid" @selected(old('style', 'solid') === 'solid')>Solid</option>
                        <option value="wireframe" @selected(old('style') === 'wireframe')>Wireframe</option>
                        <option value="transparent" @selected(old('style') === 'transparent')>Transparent</option>
                    </select>
                    @error('style') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-5">
                <h3 class="text-sm font-medium mb-3 text-gray-400 uppercase tracking-wider text-xs">Position</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="position_x" class="block text-xs mb-1">X</label>
                        <input type="number" id="position_x" name="position_x" value="{{ old('position_x', '0') }}" step="0.01"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    </div>
                    <div>
                        <label for="position_y" class="block text-xs mb-1">Y</label>
                        <input type="number" id="position_y" name="position_y" value="{{ old('position_y', '0') }}" step="0.01"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    </div>
                    <div>
                        <label for="position_z" class="block text-xs mb-1">Z</label>
                        <input type="number" id="position_z" name="position_z" value="{{ old('position_z', '0') }}" step="0.01"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    </div>
                </div>
                @error('position_x') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                @error('position_y') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                @error('position_z') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <h3 class="text-sm font-medium mb-3 text-gray-400 uppercase tracking-wider text-xs">Rotation (degrees)</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="rotation_x" class="block text-xs mb-1">X</label>
                        <input type="number" id="rotation_x" name="rotation_x" value="{{ old('rotation_x', '0') }}" step="0.01"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    </div>
                    <div>
                        <label for="rotation_y" class="block text-xs mb-1">Y</label>
                        <input type="number" id="rotation_y" name="rotation_y" value="{{ old('rotation_y', '0') }}" step="0.01"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    </div>
                    <div>
                        <label for="rotation_z" class="block text-xs mb-1">Z</label>
                        <input type="number" id="rotation_z" name="rotation_z" value="{{ old('rotation_z', '0') }}" step="0.01"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    </div>
                </div>
            </div>

            <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Create Manipulation</button>

            <div id="colorsModal" style="display:{{ old('random_color') ? 'flex' : 'none' }};position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
                <div style="background:rgba(20,20,35,0.95);border:1px solid rgba(255,255,255,0.1);border-radius:8px;padding:1.5rem;min-width:320px;text-align:center;">
                    <h3 style="color:#fff;font-weight:300;letter-spacing:1px;margin:0 0 1rem 0;font-size:1rem;">Random Colors</h3>
                    <p style="color:rgba(255,255,255,0.4);font-size:0.75rem;margin-bottom:1rem;">Choose 3 colors for the random palette</p>
                    <div style="display:flex;gap:0.75rem;justify-content:center;margin-bottom:1rem;">
                        <div>
                            <label style="color:rgba(255,255,255,0.5);font-size:0.65rem;display:block;margin-bottom:0.25rem;">#1</label>
                            <input type="color" name="colors[0]" value="{{ old('colors.0', '#ff6b6b') }}" style="width:48px;height:48px;border:2px solid rgba(255,255,255,0.1);border-radius:6px;cursor:pointer;background:transparent;">
                        </div>
                        <div>
                            <label style="color:rgba(255,255,255,0.5);font-size:0.65rem;display:block;margin-bottom:0.25rem;">#2</label>
                            <input type="color" name="colors[1]" value="{{ old('colors.1', '#51cf66') }}" style="width:48px;height:48px;border:2px solid rgba(255,255,255,0.1);border-radius:6px;cursor:pointer;background:transparent;">
                        </div>
                        <div>
                            <label style="color:rgba(255,255,255,0.5);font-size:0.65rem;display:block;margin-bottom:0.25rem;">#3</label>
                            <input type="color" name="colors[2]" value="{{ old('colors.2', '#5c7cfa') }}" style="width:48px;height:48px;border:2px solid rgba(255,255,255,0.1);border-radius:6px;cursor:pointer;background:transparent;">
                        </div>
                    </div>
                    <button type="button" onclick="document.getElementById('colorsModal').style.display='none'" style="background:transparent;border:1px solid rgba(255,255,255,0.15);color:rgba(255,255,255,0.4);padding:0.4rem 1.5rem;border-radius:4px;font-size:0.8rem;cursor:pointer;letter-spacing:1px;">Done</button>
                </div>
            </div>
        </form>
    </div>
@endsection
