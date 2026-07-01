@extends('layouts.guest')
@section('title', 'Reset Password')

@section('content')
<div class="text-center mb-8">
    <h1 class="text-xl font-bold text-white/90">Set new password</h1>
    <p class="text-sm text-white/40 mt-1">Choose a new password for your account.</p>
</div>

<form method="POST" action="{{ route('password.store') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="mb-5">
        <label for="email" class="auth-label block mb-2">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
            class="auth-input w-full rounded-xl px-4 py-3 text-sm"
            placeholder="you@example.com">
        @error('email')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <label for="password" class="auth-label block mb-2">New Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password"
            class="auth-input w-full rounded-xl px-4 py-3 text-sm"
            placeholder="Min. 8 characters">
        @error('password')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6">
        <label for="password_confirmation" class="auth-label block mb-2">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
            class="auth-input w-full rounded-xl px-4 py-3 text-sm"
            placeholder="Repeat password">
    </div>

    <button type="submit" class="btn-primary w-full rounded-xl py-3 text-sm font-semibold text-white">
        Reset Password
    </button>
</form>
@endsection
