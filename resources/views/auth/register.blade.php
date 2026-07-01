@extends('layouts.guest')
@section('title', 'Register')

@section('content')
<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
    @csrf

    <div class="text-center mb-8">
        <h1 class="text-xl font-bold text-white/90">Create account</h1>
        <p class="text-sm text-white/40 mt-1">Start exploring 3D models</p>
    </div>

    <div class="mb-5">
        <label for="name" class="auth-label block mb-2">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
            class="auth-input w-full rounded-xl px-4 py-3 text-sm"
            placeholder="Your name">
        @error('name')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <label for="email" class="auth-label block mb-2">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
            class="auth-input w-full rounded-xl px-4 py-3 text-sm"
            placeholder="you@example.com">
        @error('email')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <label for="password" class="auth-label block mb-2">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password"
            class="auth-input w-full rounded-xl px-4 py-3 text-sm"
            placeholder="Min. 8 characters">
        @error('password')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-5">
        <label for="password_confirmation" class="auth-label block mb-2">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
            class="auth-input w-full rounded-xl px-4 py-3 text-sm"
            placeholder="Repeat password">
    </div>

    <div class="mb-6">
        <label for="photo" class="auth-label block mb-2">Profile Photo <span class="text-white/20 font-normal">(optional)</span></label>
        <input id="photo" type="file" name="photo" accept="image/*"
            class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-indigo-600/30 file:text-indigo-300 hover:file:bg-indigo-600/40 file:cursor-pointer cursor-pointer">
        @error('photo')
            <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn-primary w-full rounded-xl py-3 text-sm font-semibold text-white">
        Register
    </button>

    <p class="mt-6 text-center text-sm text-white/30">
        Already have an account?
        <a href="{{ route('login') }}" class="auth-link font-medium">Log in</a>
    </p>
</form>
@endsection
