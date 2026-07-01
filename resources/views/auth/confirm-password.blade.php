@extends('layouts.guest')
@section('title', 'Confirm Password')

@section('content')
<div class="text-center mb-8">
    <h1 class="text-xl font-bold text-white/90">Confirm password</h1>
    <p class="text-sm text-white/40 mt-1">This is a secure area. Please confirm your password before continuing.</p>
</div>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <div class="mb-6">
        <label for="password" class="auth-label block mb-2">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password"
            class="auth-input w-full rounded-xl px-4 py-3 text-sm"
            placeholder="Enter your password">
        @error('password')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn-primary w-full rounded-xl py-3 text-sm font-semibold text-white">
        Confirm
    </button>
</form>
@endsection
