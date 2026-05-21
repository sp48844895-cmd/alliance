@extends('layouts.admin')

@section('title', 'Contact message')
@section('page_title', 'Message details')

@section('breadcrumb')
    <a href="{{ route('admin.contact-messages.index') }}">Contact Messages</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[240px]">{{ $message->subject }}</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
@endsection

@section('content')
    @php
        $sentAt = \Illuminate\Support\Carbon::parse($message->created_at);
        $pill = match($message->status) {
            'replied' => 'pill-leaf',
            'read' => 'pill-mute',
            default => 'pill-clay',
        };
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card p-5 lg:p-6">
                <h2 class="font-display text-2xl text-[var(--color-ink-2)] mb-4">{{ $message->subject }}</h2>

                <div class="flex items-start gap-3 mb-5">
                    <div class="w-10 h-10 rounded-full bg-[var(--color-clay-100)] text-[var(--color-clay-700)] font-semibold flex items-center justify-center text-sm shrink-0">
                        {{ strtoupper(substr($message->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-[var(--color-ink-2)]">{{ $message->name }}</div>
                        <div class="text-sm text-[var(--color-mute)] break-all">{{ $message->email }}</div>
                        @if($message->phone)
                            <div class="text-sm text-[var(--color-mute)] mt-0.5"><i class="bi bi-telephone text-xs"></i> {{ $message->phone }}</div>
                        @endif
                        <div class="text-xs text-[var(--color-mute-2)] mt-1">{{ $sentAt->format('d M Y · h:i A') }}</div>
                    </div>
                </div>

                <hr class="my-5 border-[var(--color-line)]">

                <div class="whitespace-pre-line text-[var(--color-ink)] leading-relaxed">{{ $message->message }}</div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="card p-5">
                <div class="label">Status</div>
                <span class="pill {{ $pill }} mt-2">{{ ucfirst($message->status) }}</span>

                <div class="mt-4 space-y-2">
                    @if($message->status !== 'read' && $message->status !== 'replied')
                        <form method="POST" action="{{ route('admin.contact-messages.markRead', $message->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm w-full">
                                <i class="bi bi-eye"></i> Mark as read
                            </button>
                        </form>
                    @endif
                    @if($message->status !== 'replied')
                        <form method="POST" action="{{ route('admin.contact-messages.markReplied', $message->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm w-full">
                                <i class="bi bi-reply"></i> Mark as replied
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . $message->subject) }}" class="btn btn-ghost btn-sm w-full card p-4 justify-center">
                <i class="bi bi-envelope-arrow-up"></i>
                <span>Reply via email</span>
            </a>

            <form method="POST" action="{{ route('admin.contact-messages.destroy', $message->id) }}" data-confirm="Delete this message permanently?">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-full">
                    <i class="bi bi-trash"></i> Delete message
                </button>
            </form>
        </div>
    </div>
@endsection
