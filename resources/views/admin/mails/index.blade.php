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

    @if(($type ?? 'contact') === 'contact')
        <form method="GET" action="{{ route('admin.mails.index') }}" class="card p-4 mb-5">
            <input type="hidden" name="type" value="contact">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-8">
                    <label class="label" for="status">Status</label>
                    <select id="status" name="status" class="select">
                        <option value="" @selected($filters['status'] === null || $filters['status'] === '')>All</option>
                        <option value="0" @selected((string) $filters['status'] === '0')>Unread</option>
                        <option value="1" @selected((string) $filters['status'] === '1')>Read</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-1">
                        <i class="bi bi-funnel"></i>
                        <span>Apply filters</span>
                    </button>
                    @if(($filters['status'] ?? null) !== null && ($filters['status'] ?? '') !== '')
                        <a href="{{ route('admin.mails.index', ['type' => 'contact']) }}" class="btn btn-ghost btn-sm">Clear</a>
                    @endif
                </div>
            </div>
        </form>
    @endif

    @if(($type ?? 'contact') === 'newsletter')
        <x-admin.datatable-card
            :empty="$subscribers->isEmpty()"
            empty-icon="bi-inbox"
            empty-title="No subscribers yet"
            empty-text="Newsletter sign-ups from the site will appear here.">
            <table data-admin-datatable class="table w-full">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>IP address</th>
                        <th>Subscribed</th>
                        <th class="no-sort col-actions text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscribers as $row)
                        <tr>
                            <td>
                                <a href="mailto:{{ $row->email }}" class="font-semibold text-[var(--color-ink-2)] hover:text-[var(--color-clay-600)]">
                                    {{ $row->email }}
                                </a>
                            </td>
                            <td class="text-sm text-[var(--color-mute)]">{{ $row->ip_address ?: '—' }}</td>
                            <td class="text-xs text-[var(--color-mute)] whitespace-nowrap">
                                {{ \Illuminate\Support\Carbon::parse($row->subscribed_at)->format('d M Y') }}
                                <div>{{ \Illuminate\Support\Carbon::parse($row->subscribed_at)->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    <form method="POST" action="{{ route('admin.newsletter-subscribers.destroy', $row->id) }}" class="inline" data-confirm="Remove this subscriber?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Remove">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-admin.datatable-card>
    @else
        <x-admin.datatable-card
            :empty="$mails->isEmpty()"
            empty-icon="bi-inbox"
            empty-title="No mails found"
            empty-text="Your inbox is empty for the current filters.">
            <table data-admin-datatable class="table w-full">
                <thead>
                    <tr>
                        <th class="no-sort w-10"></th>
                        <th>From</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th class="no-sort col-actions text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mails as $mail)
                        @php $isUnread = (int) $mail->status === 0; @endphp
                        <tr class="{{ $isUnread ? 'bg-[var(--color-clay-50)]' : '' }}">
                            <td>
                                @if($isUnread)
                                    <i class="bi bi-envelope text-[var(--color-clay-500)]" title="Unread"></i>
                                @else
                                    <i class="bi bi-envelope-open text-[var(--color-mute-2)]" title="Read"></i>
                                @endif
                            </td>
                            <td>
                                <div class="font-semibold text-[var(--color-ink-2)]">{{ $mail->name }}</div>
                                <div class="text-xs text-[var(--color-mute)]">{{ $mail->email }}</div>
                                <div class="text-xs text-[var(--color-mute-2)]">{{ $mail->phone !== '' ? $mail->phone : 'No phone' }}</div>
                            </td>
                            <td class="{{ $isUnread ? 'font-semibold text-[var(--color-ink-2)]' : 'text-[var(--color-ink)]' }}">
                                <div class="line-clamp-2">{{ $mail->subject }}</div>
                            </td>
                            <td class="text-xs text-[var(--color-mute)] whitespace-nowrap">
                                {{ \Illuminate\Support\Carbon::parse($mail->date)->format('d M Y') }}
                                <div>{{ \Illuminate\Support\Carbon::parse($mail->date)->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.mails.show', $mail->id) }}" class="btn btn-ghost btn-sm" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                            <form method="POST" action="{{ route('admin.mails.toggleRead', $mail->id) }}" class="inline" data-admin-ajax>
                                        @csrf
                                        <button type="submit" class="btn btn-ghost btn-sm" title="{{ $isUnread ? 'Mark read' : 'Mark unread' }}">
                                            <i class="bi {{ $isUnread ? 'bi-check2' : 'bi-envelope' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.mails.destroy', $mail->id) }}" class="inline" data-confirm="Delete this mail and all replies?">
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
        </x-admin.datatable-card>
    @endif
@endsection
