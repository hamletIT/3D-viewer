@extends('admin.layouts.app')

@section('title', $instruction->title)

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.instructions.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Instructions</a>
        <div class="flex items-center justify-between mt-2">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">{{ $instruction->title }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Order: {{ $instruction->sort_order }}
                    &middot;
                    @if ($instruction->active)
                        <span class="text-emerald-400">Active</span>
                    @else
                        <span class="text-gray-500">Inactive</span>
                    @endif
                </p>
            </div>
            @if (Auth::user()->role !== 'moderator')
                <a href="{{ route('admin.instructions.edit', $instruction) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Edit</a>
            @endif
        </div>
    </div>

    <div class="max-w-3xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        @if ($instruction->image_path)
            <div class="mb-6">
                <img src="/storage/{{ $instruction->image_path }}" alt="{{ $instruction->title }}" class="w-full h-auto rounded-lg border border-gray-700" loading="lazy">
            </div>
        @endif
        <div class="prose prose-invert max-w-none text-sm text-gray-300 leading-relaxed">
            {!! $instruction->content !!}
        </div>
    </div>

    <div class="mt-6">
        <form method="POST" action="{{ route('admin.instructions.destroy', $instruction) }}" onsubmit="return confirm('Delete instruction {{ $instruction->title }}?')">
            @csrf @method('DELETE')
            <button type="submit" class="px-4 py-2 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-sm font-medium transition-colors">Delete Instruction</button>
        </form>
    </div>
@endsection
