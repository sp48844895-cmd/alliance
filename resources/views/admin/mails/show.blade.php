@extends('layouts.admin')

@section('title', 'Mail · ' . $mail->subject)
@section('page_title', 'Mail Detail')

@section('breadcrumb')
    <a href="{{ route('admin.mails.index') }}">Contact Mails</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span class="truncate max-w-[280px]">{{ $mail->subject }}</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.mails.index') }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-arrow-left"></i>
        <span>Back to inbox</span>
    </a>
@endsection

@section('content')
    @php
        $initial = strtoupper(substr($mail->name ?: 'U', 0, 1));
        $sentDate = \Illuminate\Support\Carbon::parse($mail->date);
        $isRead = (int) $mail->status === 1;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">
            <div class="card p-5 lg:p-6">
                <h2 class="font-display text-2xl text-[var(--color-ink-2)] leading-snug">
                    {{ $mail->subject }}
                </h2>

                <div class="flex items-start gap-3 mt-5">
                    <div class="w-10 h-10 rounded-full bg-[var(--color-clay-100)] text-[var(--color-clay-700)] font-semibold flex items-center justify-center text-sm shrink-0">
                        {{ $initial }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-[var(--color-ink-2)]">{{ $mail->name }}</div>
                        <div class="text-sm text-[var(--color-mute)] break-all">{{ $mail->email }}</div>
                        @if($mail->phone !== '')
                            <div class="text-sm text-[var(--color-mute)] mt-0.5">
                                <i class="bi bi-telephone text-xs"></i>
                                {{ $mail->phone }}
                            </div>
                        @endif
                        <div class="text-xs text-[var(--color-mute-2)] mt-1">
                            {{ $sentDate->format('d M Y · h:i A') }}
                        </div>
                    </div>
                </div>

                <hr class="my-5 border-[var(--color-line)]">

                <div class="whitespace-pre-line text-[var(--color-ink)] leading-relaxed text-[15px]">{{ $mail->message }}</div>
            </div>

            <div class="card p-5 lg:p-6">
                <h3 class="font-display text-lg text-[var(--color-ink-2)] mb-4">
                    Replies
                    <span class="text-sm text-[var(--color-mute)] font-sans">({{ count($replies) }})</span>
                </h3>

                @if(count($replies) === 0)
                    <div class="py-8 text-center">
                        <i class="bi bi-chat-left-text text-3xl text-[var(--color-mute-2)]"></i>
                        <div class="text-sm text-[var(--color-mute)] mt-2">No replies sent yet.</div>
                    </div>
                @else
                    <ul class="space-y-3">
                        @foreach($replies as $reply)
                            <li class="rounded-xl border border-[var(--color-line)] bg-[var(--color-paper)] p-4">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div>
                                        <div class="text-sm font-semibold text-[var(--color-ink-2)]">
                                            {{ $reply->user }}
                                        </div>
                                        <div class="text-xs text-[var(--color-mute-2)]">
                                            {{ \Illuminate\Support\Carbon::parse($reply->date)->format('d M Y · h:i A') }}
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('admin.mails.replies.destroy', [$mail->id, $reply->id]) }}" data-confirm="Delete this reply?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm" title="Delete reply">
                                            <i class="bi bi-trash text-[var(--color-flame)]"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="whitespace-pre-line text-sm text-[var(--color-ink)] leading-relaxed">{{ $reply->reply }}</div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div class="card p-5">
                <div class="label">Status</div>
                <div class="flex items-center gap-2">
                    @if($isRead)
                        <span class="pill pill-leaf">
                            <i class="bi bi-envelope-open"></i>
                            Read
                        </span>
                    @else
                        <span class="pill pill-clay">
                            <i class="bi bi-envelope"></i>
                            Unread
                        </span>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.mails.toggleRead', $mail->id) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm w-full">
                        <i class="bi {{ $isRead ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                        <span>Mark as {{ $isRead ? 'Unread' : 'Read' }}</span>
                    </button>
                </form>

                <hr class="my-4 border-[var(--color-line)]">

                <div class="label">Sent on</div>
                <div class="text-sm text-[var(--color-ink-2)]">
                    {{ $sentDate->format('d M Y') }}
                </div>
                <div class="text-xs text-[var(--color-mute)]">
                    {{ $sentDate->format('h:i A') }}
                </div>
            </div>

            <div class="card p-5">
                <h3 class="font-display text-lg text-[var(--color-ink-2)] mb-3">Send a reply</h3>
                <form method="POST" action="{{ route('admin.mails.replies.store', $mail->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="label" for="reply">Message</label>
                        <textarea id="reply" name="reply" rows="6" class="textarea" placeholder="Write your reply..." required>{{ old('reply') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="bi bi-send"></i>
                        <span>Send Reply</span>
                    </button>
                </form>

                <a href="mailto:{{ $mail->email }}?subject={{ rawurlencode('Re: ' . $mail->subject) }}" class="btn btn-ghost btn-sm w-full mt-3">
                    <i class="bi bi-envelope-arrow-up"></i>
                    <span>Reply via email client</span>
                </a>
            </div>

            <form method="POST" action="{{ route('admin.mails.destroy', $mail->id) }}" data-confirm="Delete this mail and all its replies? This cannot be undone.">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-full">
                    <i class="bi bi-trash"></i>
                    <span>Delete Mail</span>
                </button>
            </form>
        </div>
    </div>
@endsection
