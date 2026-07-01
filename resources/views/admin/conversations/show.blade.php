@extends('admin.layouts.app')

@section('title', $conversation->subject)

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.conversations.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Conversations</a>
        <div class="flex items-center justify-between mt-2">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">{{ $conversation->subject }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    by {{ $conversation->user->name }}
                    &middot;
                    @if ($conversation->status === 'open')
                        <span class="text-emerald-400">Open</span>
                    @else
                        <span class="text-gray-500">Closed</span>
                    @endif
                    &middot;
                    {{ $conversation->messages->count() }} messages
                </p>
            </div>
            @if ($conversation->status === 'open')
                <form method="POST" action="{{ route('admin.conversations.close', $conversation) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-sm font-medium transition-colors">Close</button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.conversations.open', $conversation) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600/30 hover:bg-emerald-600/50 text-emerald-300 text-sm font-medium transition-colors">Re-open</button>
                </form>
            @endif
        </div>
    </div>

    <div class="max-w-3xl space-y-4 mb-8">
        @foreach ($conversation->messages as $msg)
            <div class="rounded-xl p-4 {{ $msg->sender_type === 'admin' ? 'bg-indigo-900/20 border border-indigo-800/30 ml-8' : 'bg-gray-900/60 border border-gray-800 mr-8' }}">
                <div class="flex items-center gap-2 mb-2">
                    @if ($msg->sender->photo)
                        <img src="{{ Storage::url($msg->sender->photo) }}" alt="" class="w-6 h-6 rounded-full object-cover" loading="lazy">
                    @else
                        <div class="w-6 h-6 rounded-full bg-indigo-600/30 flex items-center justify-center text-xs font-medium">{{ substr($msg->sender->name, 0, 2) }}</div>
                    @endif
                    <span class="text-sm font-medium {{ $msg->sender_type === 'admin' ? 'text-indigo-300' : 'text-white' }}">{{ $msg->sender->name }}</span>
                    <span class="text-xs text-gray-600">{{ $msg->sender_type === 'admin' ? 'Admin' : 'User' }}</span>
                    <span class="text-xs text-gray-600 ml-auto">{{ $msg->created_at->format('M j, Y g:i A') }}</span>
                </div>
                <p class="text-sm text-gray-300 whitespace-pre-wrap">{{ $msg->message }}</p>
                @if ($msg->image_path)
                    <img src="/storage/{{ $msg->image_path }}" alt="Attached image" class="mt-3 max-w-sm rounded-lg border border-gray-700" loading="lazy">
                @endif
            </div>
        @endforeach
    </div>

    @if ($conversation->status === 'open')
        <div class="max-w-3xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
            <h3 class="text-sm font-medium mb-4">Reply</h3>
            <form method="POST" action="{{ route('admin.conversations.reply', $conversation) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <textarea name="message" rows="4" required placeholder="Write your reply…"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors"></textarea>
                    @error('message') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center gap-4">
                    <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp"
                        class="text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-indigo-600 file:text-white file:text-xs file:font-medium hover:file:bg-indigo-500">
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors ml-auto">Send Reply</button>
                </div>
            </form>
        </div>
    @endif
@endsection
