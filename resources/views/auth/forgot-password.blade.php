@extends('layouts.guest')
@section('title', 'Forgot Password')

@section('content')
<div class="text-center mb-8">
    <h1 class="text-xl font-bold text-white/90">Reset password</h1>
    <p class="text-sm text-white/40 mt-1">Forgot your password? Enter your email and we'll send you a reset link.</p>
</div>

@if (session('status'))
    <div class="mb-5 text-sm text-emerald-400 bg-emerald-900/30 border border-emerald-700/50 rounded-xl px-4 py-3">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-6">
        <label for="email" class="auth-label block mb-2">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
            class="auth-input w-full rounded-xl px-4 py-3 text-sm"
            placeholder="you@example.com">
        @error('email')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn-primary w-full rounded-xl py-3 text-sm font-semibold text-white">
        Email Password Reset Link
    </button>

    <p class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm auth-link font-medium">Back to log in</a>
    </p>
</form>
@endsection
