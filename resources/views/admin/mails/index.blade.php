@extends('layouts.admin')

@section('title', ($type ?? 'contact') === 'newsletter' ? 'Newsletter Subscribers' : 'Contact Mails')
@section('page_title', ($type ?? 'contact') === 'newsletter' ? 'Newsletter Subscribers' : 'Contact Mails')

@section('breadcrumb')
    <span>Mails</span>
    <span class="text-[var(--color-mute-2)]">/</span>
    <span>{{ ($type ?? 'contact') === 'newsletter' ? 'Newsletter' : 'Contact' }}</span>
@endsection

@section('topbar_actions')
    @if(($type ?? 'contact') === 'contact')
        <span class="pill {{ $unreadCount > 0 ? 'pill-clay' : 'pill-mute' }}">
            <i class="bi bi-envelope"></i>
            Unread: {{ $unreadCount }}
        </span>
    @else
        <span class="pill pill-mute">
            <i class="bi bi-newspaper"></i>
            Total: {{ number_format($newsletterCount ?? 0) }}
        </span>
    @endif
@endsection

@section('content')
    <div class="flex flex-wrap gap-2 mb-5">
        <a href="{{ route('admin.mails.index', ['type' => 'contact']) }}"
           class="btn {{ ($type ?? 'contact') === 'contact' ? 'btn-primary' : 'btn-ghost' }} btn-sm">
            <i class="bi bi-envelope"></i>
            <span>Contact mail</span>
        </a>
        <a href="{{ route('admin.mails.index', ['type' => 'newsletter']) }}"
           class="btn {{ ($type ?? 'contact') === 'newsletter' ? 'btn-primary' : 'btn-ghost' }} btn-sm">
            <i class="bi bi-newspaper"></i>
            <span>Newsletter</span>
            @if(($newsletterCount ?? 0) > 0)
                <span class="ml-1 text-xs opacity-80">({{ $newsletterCount }})</span>
            @endif
        </a>
    </div>

    <div class="card p-4 mb-5">
        <form method="GET" action="{{ route('admin.mails.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <input type="hidden" name="type" value="{{ $type ?? 'contact' }}">
            <div class="md:col-span-{{ ($type ?? 'contact') === 'newsletter' ? '10' : '7' }}">
                <label class="label" for="q">Search</label>
                <input id="q" type="text" name="q" value="{{ $filters['q'] }}" class="input"
                       placeholder="{{ ($type ?? 'contact') === 'newsletter' ? 'Email address' : 'Name, email or subject' }}">
            </div>
            @if(($type ?? 'contact') === 'contact')
                <div class="md:col-span-3">
                    <label class="label" for="status">Status</label>
                    <select id="status" name="status" class="select">
                        <option value="" {{ $filters['status'] === null || $filters['status'] === '' ? 'selected' : '' }}>All</option>
                        <option value="0" {{ (string) $filters['status'] === '0' ? 'selected' : '' }}>Unread</option>
                        <option value="1" {{ (string) $filters['status'] === '1' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
            @endif
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="btn btn-primary w-full">
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>
                @if($filters['q'] !== '' || (($filters['status'] ?? null) !== null && ($filters['status'] ?? '') !== ''))
                    <a href="{{ route('admin.mails.index', ['type' => $type ?? 'contact']) }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if(($type ?? 'contact') === 'newsletter')
        @if($subscribers->isEmpty())
            <div class="card p-10 text-center">
                <i class="bi bi-inbox text-4xl text-[var(--color-mute-2)]"></i>
                <h2 class="font-display text-xl mt-4">No newsletter subscribers yet</h2>
                <p class="text-sm text-[var(--color-mute)] mt-1">Emails from the home page and footer subscribe forms will appear here.</p>
            </div>
        @else
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-12">#</th>
                                <th>Email</th>
                                <th>IP address</th>
                                <th>Subscribed</th>
                                <th class="text-right pr-5">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribers as $i => $row)
                                <tr>
                                    <td class="text-[var(--color-mute)] font-mono text-xs">
                                        {{ $subscribers->firstItem() + $i }}
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $row->email }}" class="font-semibold text-[var(--color-ink-2)] hover:text-[var(--color-clay-600)]">
                                            {{ $row->email }}
                                        </a>
                                    </td>
                                    <td class="text-sm text-[var(--color-mute)]">
                                        {{ $row->ip_address ?: '—' }}
                                    </td>
                                    <td class="text-xs text-[var(--color-mute-2)] whitespace-nowrap">
                                        {{ \Illuminate\Support\Carbon::parse($row->subscribed_at)->format('d M Y') }}
                                        <div class="text-[10px]">{{ \Illuminate\Support\Carbon::parse($row->subscribed_at)->format('h:i A') }}</div>
                                    </td>
                                    <td class="text-right pr-5">
                                        <form method="POST" action="{{ route('admin.newsletter-subscribers.destroy', $row->id) }}" class="inline" data-confirm="Remove this subscriber from the list?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Remove">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-5">
                {{ $subscribers->links() }}
            </div>
        @endif
    @else
        @if($mails->isEmpty())
            <div class="card p-10 text-center">
                <i class="bi bi-inbox text-4xl text-[var(--color-mute-2)]"></i>
                <h2 class="font-display text-xl mt-4">No mails found</h2>
                <p class="text-sm text-[var(--color-mute)] mt-1">Inbox is empty for the current filters.</p>
            </div>
        @else
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-12">#</th>
                                <th class="w-12"></th>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th>Phone</th>
                                <th>Date</th>
                                <th class="text-right pr-5">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mails as $i => $mail)
                                @php $isUnread = (int) $mail->status === 0; @endphp
                                <tr class="{{ $isUnread ? 'bg-[var(--color-clay-50)]' : '' }}">
                                    <td class="text-[var(--color-mute)] font-mono text-xs">
                                        {{ $mails->firstItem() + $i }}
                                    </td>
                                    <td>
                                        @if($isUnread)
                                            <i class="bi bi-envelope text-[var(--color-clay-500)] text-lg" title="Unread"></i>
                                        @else
                                            <i class="bi bi-envelope-open text-[var(--color-mute-2)] text-lg" title="Read"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="font-semibold text-[var(--color-ink-2)] truncate max-w-[220px]">
                                            {{ $mail->name }}
                                        </div>
                                        <div class="text-xs text-[var(--color-mute)] truncate max-w-[220px]">
                                            {{ $mail->email }}
                                        </div>
                                    </td>
                                    <td class="max-w-[300px]">
                                        <div class="truncate {{ $isUnread ? 'font-semibold text-[var(--color-ink-2)]' : 'text-[var(--color-ink)]' }}">
                                            {{ $mail->subject }}
                                        </div>
                                    </td>
                                    <td class="text-sm text-[var(--color-mute)]">
                                        {{ $mail->phone !== '' ? $mail->phone : '—' }}
                                    </td>
                                    <td class="text-xs text-[var(--color-mute-2)] whitespace-nowrap">
                                        {{ \Illuminate\Support\Carbon::parse($mail->date)->format('d M Y') }}
                                        <div class="text-[10px]">{{ \Illuminate\Support\Carbon::parse($mail->date)->format('h:i A') }}</div>
                                    </td>
                                    <td class="text-right pr-5">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('admin.mails.show', $mail->id) }}" class="btn btn-ghost btn-sm" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.mails.toggleRead', $mail->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-sm" title="{{ $isUnread ? 'Mark as read' : 'Mark as unread' }}">
                                                    <i class="bi {{ $isUnread ? 'bi-eye' : 'bi-eye-slash' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.mails.destroy', $mail->id) }}" class="inline" data-confirm="Delete this mail and all its replies?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-5">
                {{ $mails->links() }}
            </div>
        @endif
    @endif
@endsection
