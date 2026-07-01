<?php

use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\FileTypeController;
use App\Http\Controllers\Admin\InstructionController;
use App\Http\Controllers\Admin\KeybindingController;
use App\Http\Controllers\Admin\LandingSectionController;
use App\Http\Controllers\Admin\ManipulationController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TextureController;
use App\Http\Controllers\Admin\UpgradeRequestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SceneController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');;

Route::get('/app', function () {
    return view('welcome');
})->middleware(['auth'])->name('app');

Route::post('/app/upload', [FileUploadController::class, 'store'])->middleware(['auth'])->name('app.upload');
Route::get('/app/sessions', [FileUploadController::class, 'sessions'])->middleware(['auth'])->name('app.sessions');
Route::get('/app/sessions/{sessionId}/files', [FileUploadController::class, 'sessionFiles'])->middleware(['auth'])->name('app.sessions.files');
Route::get('/app/manipulations', [FileUploadController::class, 'manipulations'])->middleware(['auth'])->name('app.manipulations');
Route::get('/app/keybindings', function () {
    return response()->json(
        \App\Models\Setting::where('key', 'like', 'kbd_%')->pluck('value', 'key')
    );
})->middleware(['auth'])->name('app.keybindings');

Route::get('/app/textures', function () {
    return response()->json(
        \App\Models\Texture::get(['id', 'name', 'file_path'])
    );
})->middleware(['auth'])->name('app.textures');

Route::post('/app/scenes/save', [SceneController::class, 'save'])->middleware(['auth'])->name('app.scenes.save');
Route::get('/app/scenes/{sessionId}', [SceneController::class, 'load'])->middleware(['auth'])->name('app.scenes.load');

Route::get('/app/plans', function () {
    return response()->json(
        \App\Models\Plan::where('active', true)->orderBy('sort_order')->get(['id', 'name', 'slug', 'max_sessions', 'max_objects_per_scene', 'price', 'icon', 'duration_days'])
    );
})->middleware(['auth'])->name('app.plans');

Route::get('/app/limits', function () {
    $user = auth()->user();

    if ($user->role !== 'user') {
        return response()->json([
            'max_sessions' => -1,
            'max_objects_per_scene' => -1,
            'current_sessions' => 0,
            'plan_name' => 'Unlimited',
            'plan_icon' => '∞',
            'has_pending_request' => false,
            'is_expired' => false,
            'plan_id' => null,
        ]);
    }

    $lastUp = $user->userPlans()->latest()->first();
    $isExpired = false;
    if ($lastUp && $lastUp->expires_at && $lastUp->expires_at->isPast()) {
        $plan = $lastUp->plan;
        $isExpired = true;
    } else {
        $plan = $lastUp?->plan ?? \App\Models\Plan::where('slug', 'free')->first();
    }
    $sessionCount = \App\Models\FileType::where('user_id', $user->id)
        ->whereNotNull('session_id')
        ->distinct('session_id')
        ->count('session_id');
    $hasPending = \App\Models\UpgradeRequest::where('user_id', $user->id)
        ->where('status', 'pending')
        ->exists();
    return response()->json([
        'max_sessions' => $plan->max_sessions,
        'max_objects_per_scene' => $plan->max_objects_per_scene,
        'current_sessions' => $sessionCount,
        'plan_name' => $plan->name,
        'plan_icon' => $plan->icon,
        'has_pending_request' => $hasPending,
        'is_expired' => $isExpired,
        'plan_id' => $plan->id,
    ]);
})->middleware(['auth'])->name('app.limits');

Route::post('/app/upgrade-request', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'plan_id' => 'required|exists:plans,id',
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:50',
    ]);

    $existing = \App\Models\UpgradeRequest::where('user_id', auth()->id())
        ->where('plan_id', $validated['plan_id'])
        ->where('status', 'pending')
        ->first();

    if ($existing) {
        return response()->json(['message' => 'You already have a pending request for this plan.'], 409);
    }

    $request_record = \App\Models\UpgradeRequest::create([
        'user_id' => auth()->id(),
        'plan_id' => $validated['plan_id'],
        'name' => $validated['name'] ?? auth()->user()->name,
        'email' => $validated['email'] ?? auth()->user()->email,
        'phone' => $validated['phone'] ?? '',
        'status' => 'pending',
    ]);

    $request_record->load('user', 'plan');
    try {
        $admins = \App\Models\User::whereIn('role', ['admin', 'moderator'])->get();
        \Illuminate\Support\Facades\Mail::to($admins)->send(new \App\Mail\UpgradeRequestMail($request_record));
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::warning('Failed to send upgrade request email: ' . $e->getMessage());
    }

    return response()->json(['message' => 'Upgrade request submitted.', 'request' => $request_record], 201);
})->middleware(['auth'])->name('app.upgrade-request');

Route::get('/how-to-use', function () {
    $instructions = \App\Models\Instruction::where('active', true)->orderBy('sort_order')->get();
    return view('how-to-use', compact('instructions'));
})->name('how-to-use');

Route::middleware(['auth', 'verified', 'role:admin,moderator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');

    Route::resource('users', UserController::class);
    Route::patch('users/{user}/change-plan', [UserController::class, 'changePlan'])->name('users.change-plan');
    Route::delete('users/{user}/remove-plan', [UserController::class, 'removePlan'])->name('users.remove-plan');
    Route::resource('settings', SettingController::class);

    Route::get('file-types', [FileTypeController::class, 'index'])->name('file-types.index');
    Route::get('file-types/{fileType}', [FileTypeController::class, 'show'])->name('file-types.show');
    Route::delete('file-types/{fileType}', [FileTypeController::class, 'destroy'])->name('file-types.destroy');

    Route::resource('manipulations', ManipulationController::class);
    Route::resource('instructions', InstructionController::class);
    Route::resource('plans', PlanController::class);
    Route::resource('textures', TextureController::class);
    Route::resource('upgrade-requests', UpgradeRequestController::class)->only(['index', 'show']);
    Route::patch('upgrade-requests/{upgrade_request}/approve', [UpgradeRequestController::class, 'approve'])->name('upgrade-requests.approve');
    Route::patch('upgrade-requests/{upgrade_request}/reject', [UpgradeRequestController::class, 'reject'])->name('upgrade-requests.reject');

    Route::get('keybindings', [KeybindingController::class, 'index'])->name('keybindings.index');
    Route::patch('keybindings', [KeybindingController::class, 'update'])->name('keybindings.update');

    Route::get('conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('conversations/{conversation}/reply', [ConversationController::class, 'reply'])->name('conversations.reply');
    Route::patch('conversations/{conversation}/close', [ConversationController::class, 'close'])->name('conversations.close');
    Route::patch('conversations/{conversation}/open', [ConversationController::class, 'open'])->name('conversations.open');

    Route::resource('landing-sections', LandingSectionController::class);
    Route::post('landing-sections/{landingSection}/features', [LandingSectionController::class, 'storeFeature'])->name('landing-sections.features.store');
    Route::patch('landing-features/{landingFeature}', [LandingSectionController::class, 'updateFeature'])->name('landing-features.update');
    Route::delete('landing-features/{landingFeature}', [LandingSectionController::class, 'destroyFeature'])->name('landing-features.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/dashboard/conversations', [UserDashboardController::class, 'storeConversation'])->name('user.conversations.store');
    Route::post('/dashboard/conversations/{conversation}/messages', [UserDashboardController::class, 'storeMessage'])->name('user.conversations.message');
});

require __DIR__.'/auth.php';
