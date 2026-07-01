@extends('admin.layouts.app')

@section('title', 'Keyboard Shortcuts')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Keyboard Shortcuts</h1>
    </div>

    <div class="max-w-4xl">
        <form method="POST" action="{{ route('admin.keybindings.update') }}">
            @csrf @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @php
                    $categories = [
                        'Actions' => ['kbd_recolor', 'kbd_duplicate', 'kbd_select', 'kbd_place', 'kbd_delete', 'kbd_undo'],
                        'Transform Modes' => ['kbd_translate', 'kbd_rotate', 'kbd_scale'],
                        'Movement' => ['kbd_fwd', 'kbd_back', 'kbd_left', 'kbd_right', 'kbd_up', 'kbd_down', 'kbd_sprint'],
                    ];
                    $labels = [
                        'kbd_recolor' => 'Recolor',
                        'kbd_duplicate' => 'Duplicate Object',
                        'kbd_select' => 'Select Object',
                        'kbd_place' => 'Place Object',
                        'kbd_delete' => 'Delete Object',
                        'kbd_undo' => 'Undo',
                        'kbd_translate' => 'Translate Mode',
                        'kbd_rotate' => 'Rotate Mode',
                        'kbd_scale' => 'Scale Mode',
                        'kbd_fwd' => 'Move Forward',
                        'kbd_back' => 'Move Backward',
                        'kbd_left' => 'Strafe Left',
                        'kbd_right' => 'Strafe Right',
                        'kbd_up' => 'Move Up',
                        'kbd_down' => 'Move Down',
                        'kbd_sprint' => 'Sprint',
                    ];
                @endphp

                @foreach ($categories as $category => $settingKeys)
                    <div class="rounded-xl bg-gray-900/60 border border-gray-800 p-5">
                        <h3 class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-4 pb-3 border-b border-gray-800">{{ $category }}</h3>
                        <div class="space-y-3">
                            @foreach ($settingKeys as $sk)
                                @php $setting = $bindings->get($sk); @endphp
                                @if ($setting)
                                    <div class="flex items-center justify-between gap-3">
                                        <label for="{{ $sk }}" class="text-sm text-gray-300 shrink-0">{{ $labels[$sk] ?? $sk }}</label>
                                        <input type="text" id="{{ $sk }}" name="bindings[{{ $sk }}]"
                                            value="{{ old("bindings.{$sk}", $setting->value) }}"
                                            class="w-24 rounded-lg bg-gray-950 border border-gray-700 px-2.5 py-1.5 text-sm font-mono text-center focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all uppercase tracking-wider placeholder:text-gray-600"
                                            onfocus="this.select()"
                                            placeholder="key"
                                            maxlength="20">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Save Shortcuts</button>
                <a href="{{ route('admin.keybindings.index') }}" class="px-6 py-2.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-sm font-medium transition-colors">Reset</a>
            </div>
        </form>
    </div>

    <div class="max-w-4xl mt-8 rounded-xl bg-gray-900/60 border border-gray-800 overflow-hidden">
        <details class="group">
            <summary class="flex items-center gap-2 px-6 py-4 text-sm font-medium text-indigo-400 cursor-pointer select-none hover:text-indigo-300 transition-colors">
                <svg class="w-4 h-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                Key Code Reference
            </summary>
            <div class="px-6 pb-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-xs font-mono">
                <div>
                    <h4 class="text-gray-500 font-semibold uppercase tracking-wider mb-2 text-[10px]">Letters</h4>
                    <div class="space-y-0.5">
                        @foreach (range('A', 'Z') as $letter)
                            <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">{{ $letter }}</span><span class="text-indigo-300">Key{{ $letter }}</span></div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h4 class="text-gray-500 font-semibold uppercase tracking-wider mb-2 text-[10px]">Digits</h4>
                    <div class="space-y-0.5">
                        @foreach (range(0, 9) as $digit)
                            <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">{{ $digit }}</span><span class="text-indigo-300">Digit{{ $digit }}</span></div>
                        @endforeach
                    </div>
                    <h4 class="text-gray-500 font-semibold uppercase tracking-wider mb-2 mt-5 text-[10px]">Modifiers</h4>
                    <div class="space-y-0.5">
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Shift L</span><span class="text-indigo-300">ShiftLeft</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Shift R</span><span class="text-indigo-300">ShiftRight</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Ctrl L</span><span class="text-indigo-300">ControlLeft</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Ctrl R</span><span class="text-indigo-300">ControlRight</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Alt L</span><span class="text-indigo-300">AltLeft</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Alt R</span><span class="text-indigo-300">AltRight</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Cmd L</span><span class="text-indigo-300">MetaLeft</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Cmd R</span><span class="text-indigo-300">MetaRight</span></div>
                    </div>
                </div>
                <div>
                    <h4 class="text-gray-500 font-semibold uppercase tracking-wider mb-2 text-[10px]">Navigation</h4>
                    <div class="space-y-0.5">
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">↑</span><span class="text-indigo-300">ArrowUp</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">↓</span><span class="text-indigo-300">ArrowDown</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">←</span><span class="text-indigo-300">ArrowLeft</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">→</span><span class="text-indigo-300">ArrowRight</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Home</span><span class="text-indigo-300">Home</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">End</span><span class="text-indigo-300">End</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Pg Up</span><span class="text-indigo-300">PageUp</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Pg Dn</span><span class="text-indigo-300">PageDown</span></div>
                    </div>
                    <h4 class="text-gray-500 font-semibold uppercase tracking-wider mb-2 mt-5 text-[10px]">Action</h4>
                    <div class="space-y-0.5">
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Enter</span><span class="text-indigo-300">Enter</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Esc</span><span class="text-indigo-300">Escape</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Tab</span><span class="text-indigo-300">Tab</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Space</span><span class="text-indigo-300">Space</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Bksp</span><span class="text-indigo-300">Backspace</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Del</span><span class="text-indigo-300">Delete</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">Ins</span><span class="text-indigo-300">Insert</span></div>
                    </div>
                </div>
                <div>
                    <h4 class="text-gray-500 font-semibold uppercase tracking-wider mb-2 text-[10px]">Function</h4>
                    <div class="grid grid-cols-2 gap-x-3 gap-y-0.5">
                        @foreach (range(1, 12) as $n)
                            <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">F{{ $n }}</span><span class="text-indigo-300">F{{ $n }}</span></div>
                        @endforeach
                    </div>
                    <h4 class="text-gray-500 font-semibold uppercase tracking-wider mb-2 mt-5 text-[10px]">Symbols</h4>
                    <div class="space-y-0.5">
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">-</span><span class="text-indigo-300">Minus</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">=</span><span class="text-indigo-300">Equal</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">[</span><span class="text-indigo-300">BracketLeft</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">]</span><span class="text-indigo-300">BracketRight</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">\</span><span class="text-indigo-300">Backslash</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">;</span><span class="text-indigo-300">Semicolon</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">'</span><span class="text-indigo-300">Quote</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">,</span><span class="text-indigo-300">Comma</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">.</span><span class="text-indigo-300">Period</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">/</span><span class="text-indigo-300">Slash</span></div>
                        <div class="flex justify-between py-0.5 px-1.5 rounded hover:bg-gray-800/40"><span class="text-gray-400">`</span><span class="text-indigo-300">Backquote</span></div>
                    </div>
                </div>
            </div>
            <div class="px-6 pb-4 border-t border-gray-800 pt-3">
                <p class="text-[10px] text-gray-600">Full reference: <a href="https://developer.mozilla.org/en-US/docs/Web/API/UI_Events/Keyboard_event_code_values" target="_blank" class="text-indigo-500 hover:text-indigo-400">MDN KeyboardEvent code values</a></p>
            </div>
        </details>
    </div>
@endsection
