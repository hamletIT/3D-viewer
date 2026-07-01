@extends('layouts.guest')
@section('title', 'Log in')

@section('content')
@if (session('status'))
    <div class="mb-5 text-sm text-emerald-400 bg-emerald-900/30 border border-emerald-700/50 rounded-lg px-4 py-3">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="text-center mb-8">
        <h1 class="text-xl font-bold text-white/90">Welcome back</h1>
        <p class="text-sm text-white/40 mt-1">Sign in to your account</p>
    </div>

    <div class="mb-5">
        <label for="email" class="auth-label block mb-2">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
            class="auth-input w-full rounded-xl px-4 py-3 text-sm"
            placeholder="you@example.com">
        @error('email')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <label for="password" class="auth-label block mb-2">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password"
            class="auth-input w-full rounded-xl px-4 py-3 text-sm"
            placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;">
        @error('password')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between mb-6">
        <label for="remember_me" class="flex items-center gap-2 text-sm auth-link cursor-pointer">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-white/10 bg-white/5">
            <span>Remember me</span>
        </label>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm auth-link">Forgot password?</a>
        @endif
    </div>

    <button type="submit" class="btn-primary w-full rounded-xl py-3 text-sm font-semibold text-white">
        Log in
    </button>

    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-white/30">
            Don't have an account?
            <a href="{{ route('register') }}" class="auth-link font-medium">Register</a>
        </p>
    @endif
</form>
@endsection
