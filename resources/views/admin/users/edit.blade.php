@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Users</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">Edit User</h1>
    </div>

    <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
            @csrf @method('PATCH')

            <div class="mb-5">
                <label for="name" class="block text-sm font-medium mb-2">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors placeholder-gray-500">
                @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label for="email" class="block text-sm font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors placeholder-gray-500">
                @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label for="password" class="block text-sm font-medium mb-2">New Password <span class="text-gray-500 font-normal">(leave blank to keep current)</span></label>
                <input type="password" id="password" name="password"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors placeholder-gray-500">
                @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label for="password_confirmation" class="block text-sm font-medium mb-2">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors placeholder-gray-500">
            </div>

            <div class="mb-5">
                <label for="photo" class="block text-sm font-medium mb-2">Photo</label>
                @if ($user->photo)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ Storage::url($user->photo) }}" alt="" class="w-12 h-12 rounded-full object-cover" loading="lazy">
                        <span class="text-sm text-gray-400">Current photo</span>
                    </div>
                @endif
                <input type="file" id="photo" name="photo" accept="image/*"
                    class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-600/20 file:text-indigo-300 hover:file:bg-indigo-600/30 file:cursor-pointer cursor-pointer">
                @error('photo') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label for="role" class="block text-sm font-medium mb-2">Role</label>
                <select id="role" name="role" required
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    <option value="user" @selected(old('role', $user->role) === 'user')>User</option>
                    <option value="moderator" @selected(old('role', $user->role) === 'moderator')>Moderator</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                </select>
                @error('role') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Update User</button>
        </form>
    </div>

    <div class="max-w-2xl mt-8 rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Plan Management</h2>

        <div class="mb-5">
            <label class="block text-sm font-medium mb-2">Current Plan</label>
            <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-800/50 border border-gray-700">
                <span class="text-xl">{{ $currentPlan?->icon ?? '🆓' }}</span>
                <div>
                    <span class="text-white font-medium">{{ $currentPlan?->name ?? 'Free' }}</span>
                    <span class="text-gray-500 text-xs ml-2">
                        {{ $currentPlan ? ($currentPlan->max_sessions === -1 ? '∞ sessions' : $currentPlan->max_sessions . ' sessions') : '5 sessions' }}
                        &middot;
                        {{ $currentPlan ? ($currentPlan->max_objects_per_scene === -1 ? '∞ objects' : $currentPlan->max_objects_per_scene . ' objects') : '5 objects' }}
                    </span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.change-plan', $user) }}" class="mb-4">
            @csrf @method('PATCH')
            <label for="plan_id" class="block text-sm font-medium mb-2">Switch to Plan</label>
            <div class="flex items-center gap-3">
                <select id="plan_id" name="plan_id" required
                    class="flex-1 rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}" @selected($currentPlan?->id === $plan->id)>
                            {{ $plan->icon }} {{ $plan->name }}
                            @if ($plan->slug === 'free') (reset) @else (${{ number_format($plan->price, 2) }}) @endif
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors whitespace-nowrap">Apply</button>
            </div>
        </form>

        @if ($currentPlan && $currentPlan->slug !== 'free')
            <form method="POST" action="{{ route('admin.users.remove-plan', $user) }}" onsubmit="return confirm('Reset {{ $user->name }} to Free plan?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-sm font-medium transition-colors">Reset to Free</button>
            </form>
        @endif
    </div>
@endsection
