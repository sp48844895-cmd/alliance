@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('page_title', 'Contact Messages')

@section('breadcrumb')
    <span>Contact Messages</span>
@endsection

@section('topbar_actions')
    <span class="pill {{ $stats['new'] > 0 ? 'pill-clay' : 'pill-mute' }}">
        <i class="bi bi-envelope"></i>
        New: {{ $stats['new'] }}
    </span>
@endsection

@section('content')
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
        <div class="stat p-4">
            <div class="stat-label">Total</div>
            <div class="stat-num mt-1">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="stat p-4">
            <div class="stat-label">New</div>
            <div class="stat-num mt-1 text-[var(--color-clay-600)]">{{ number_format($stats['new']) }}</div>
        </div>
        <div class="stat p-4">
            <div class="stat-label">Read</div>
            <div class="stat-num mt-1">{{ number_format($stats['read']) }}</div>
        </div>
        <div class="stat p-4">
            <div class="stat-label">Replied</div>
            <div class="stat-num mt-1 text-[var(--color-leaf)]">{{ number_format($stats['replied']) }}</div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="card p-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-4">
                <label class="label" for="q">Search</label>
                <input id="q" type="search" name="q" value="{{ $filters['q'] }}" class="input" placeholder="Name, email, phone, subject">
            </div>
            <div class="md:col-span-2">
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="select">
                    <option value="">All</option>
                    <option value="new" @selected(($filters['status'] ?? '') === 'new')>New</option>
                    <option value="read" @selected(($filters['status'] ?? '') === 'read')>Read</option>
                    <option value="replied" @selected(($filters['status'] ?? '') === 'replied')>Replied</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="label" for="date_from">From</label>
                <input id="date_from" type="date" name="date_from" value="{{ $filters['date_from'] }}" class="input">
            </div>
            <div class="md:col-span-2">
                <label class="label" for="date_to">To</label>
                <input id="date_to" type="date" name="date_to" value="{{ $filters['date_to'] }}" class="input">
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-ghost btn-sm">Reset</a>
            </div>
        </div>
    </form>

    @if($messages->isEmpty())
        <div class="card p-10 text-center">
            <i class="bi bi-inbox text-4xl text-[var(--color-mute-2)]"></i>
            <h2 class="font-display text-xl mt-4">No messages found</h2>
            <p class="text-sm text-[var(--color-mute)] mt-1">Submissions from the contact form will appear here.</p>
        </div>
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th>Sender</th>
                            <th>Subject</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $i => $msg)
                            @php
                                $pill = match($msg->status) {
                                    'replied' => 'pill-leaf',
                                    'read' => 'pill-mute',
                                    default => 'pill-clay',
                                };
                                $isNew = $msg->status === 'new';
                            @endphp
                            <tr class="{{ $isNew ? 'bg-[var(--color-clay-50)]' : '' }}">
                                <td class="text-xs text-[var(--color-mute)] font-mono">{{ $messages->firstItem() + $i }}</td>
                                <td>
                                    <div class="font-semibold text-[var(--color-ink-2)] truncate max-w-[200px]">{{ $msg->name }}</div>
                                    <div class="text-xs text-[var(--color-mute)] truncate max-w-[200px]">{{ $msg->email }}</div>
                                </td>
                                <td class="max-w-[240px] truncate {{ $isNew ? 'font-semibold' : '' }}">{{ $msg->subject }}</td>
                                <td class="text-sm text-[var(--color-mute)]">{{ $msg->phone ?: '—' }}</td>
                                <td><span class="pill {{ $pill }}">{{ ucfirst($msg->status) }}</span></td>
                                <td class="text-xs text-[var(--color-mute)] whitespace-nowrap">
                                    {{ \Illuminate\Support\Carbon::parse($msg->created_at)->format('d M Y') }}
                                    <div class="text-[10px] block">{{ \Illuminate\Support\Carbon::parse($msg->created_at)->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.contact-messages.show', $msg->id) }}" class="btn btn-ghost btn-sm" title="View"><i class="bi bi-eye"></i></a>
                                        @if($msg->status !== 'read' && $msg->status !== 'replied')
                                            <form method="POST" action="{{ route('admin.contact-messages.markRead', $msg->id) }}" class="inline">@csrf<button type="submit" class="btn btn-ghost btn-sm" title="Mark read"><i class="bi bi-eye"></i></button></form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.contact-messages.destroy', $msg->id) }}" class="inline" data-confirm="Delete this message?">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="bi bi-trash"></i></button></form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-5">{{ $messages->withQueryString()->links() }}</div>
    @endif
@endsection
