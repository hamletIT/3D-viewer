<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KeybindingController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role === 'moderator') {
                abort(403, 'Moderators cannot modify key bindings.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $bindings = Setting::where('key', 'like', 'kbd_%')
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        return view('admin.keybindings.index', compact('bindings'));
    }

    public function update(Request $request)
    {
        $keys = Setting::where('key', 'like', 'kbd_%')->pluck('key');

        $rules = [];
        $messages = [];
        foreach ($keys as $key) {
            $label = str_replace('kbd_', '', $key);
            $rules["bindings.{$key}"] = ['required', 'string', 'min:1', 'max:50'];
            $messages["bindings.{$key}.required"] = "The {$label} binding cannot be empty.";
            $messages["bindings.{$key}.min"] = "The {$label} binding cannot be empty.";
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request) {
            $values = array_filter(array_values($request->input('bindings', [])), 'is_string');
            $counts = array_count_values($values);
            foreach ($counts as $code => $count) {
                if ($count > 1) {
                    $validator->errors()->add('bindings', "Key code \"{$code}\" is used {$count} times. Each key must be unique.");
                }
            }
        });

        $validated = $validator->validate();

        foreach ($validated['bindings'] as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->route('admin.keybindings.index')
            ->with('success', 'Keyboard shortcuts updated successfully.');
    }
}
