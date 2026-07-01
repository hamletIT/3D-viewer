@extends('layouts.guest')
@section('title', 'Verify Email')

@section('content')
<div class="text-center mb-8">
    <h1 class="text-xl font-bold text-white/90">Verify your email</h1>
    <p class="text-sm text-white/40 mt-1">Thanks for signing up! Before getting started, could you verify your email address by clicking the link we just emailed you?</p>
</div>

@if (session('status') == 'verification-link-sent')
    <div class="mb-5 text-sm text-emerald-400 bg-emerald-900/30 border border-emerald-700/50 rounded-xl px-4 py-3">
        A new verification link has been sent to your email address.
    </div>
@endif

<div class="flex items-center justify-between gap-4">
    <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
        @csrf
        <button type="submit" class="btn-primary w-full rounded-xl py-3 text-sm font-semibold text-white">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="auth-link text-sm font-medium px-4 py-3">
            Log Out
        </button>
    </form>
</div>
@endsection
