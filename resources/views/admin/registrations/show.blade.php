@extends('layouts.admin')

@section('title', 'Application details')
@section('page_title', 'Application details')

@section('breadcrumb')
    <a href="{{ route('admin.registrations.index') }}">Join Applications</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span>{{ $registration->full_name }}</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.registrations.index') }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-arrow-left"></i>
        <span>Back</span>
    </a>
@endsection

@section('content')
    @php
        $sentAt = \Illuminate\Support\Carbon::parse($registration->created_at);
        $pill = match($registration->status) {
            'accepted' => 'pill-leaf',
            'rejected' => 'pill-mute',
            'reviewed' => 'pill-river',
            default => 'pill-clay',
        };
    @endphp

    @if(session('success'))
        <div class="alert alert-success mb-4 text-sm">
            <i class="bi bi-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="card p-5 lg:p-6">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="pill pill-mute">{{ $typeLabels[$registration->type] ?? ucfirst($registration->type) }}</span>
                    <span class="pill {{ $pill }}">{{ ucfirst($registration->status) }}</span>
                </div>

                <h2 class="font-display text-2xl text-[var(--color-ink-2)] mb-4">{{ $registration->full_name }}</h2>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="label mb-1">Email</dt>
                        <dd class="text-[var(--color-ink-2)] break-all"><a href="mailto:{{ $registration->email }}" class="text-[var(--color-clay-600)] hover:underline">{{ $registration->email }}</a></dd>
                    </div>
                    <div>
                        <dt class="label mb-1">Phone</dt>
                        <dd class="text-[var(--color-ink-2)]">{{ $registration->phone }}</dd>
                    </div>
                    @if(in_array($registration->type, ['intern', 'fellow', 'partner', 'volunteer'], true))
                    <div>
                        <dt class="label mb-1">
                            @if($registration->type === 'intern')
                                University / College
                            @elseif($registration->type === 'partner')
                                Subject
                            @elseif($registration->type === 'volunteer')
                                Subject
                            @else
                                Organisation
                            @endif
                        </dt>
                        <dd class="text-[var(--color-ink-2)]">{{ $registration->institution ?: '—' }}</dd>
                    </div>
                    @endif
                    @if($registration->type === 'intern' && $registration->class_year)
                        <div>
                            <dt class="label mb-1">Class / Year</dt>
                            <dd class="text-[var(--color-ink-2)]">{{ $registration->class_year }}</dd>
                        </div>
                    @endif
                    @if($registration->type === 'intern' && $registration->domain_area)
                        <div>
                            <dt class="label mb-1">Domain area</dt>
                            <dd class="text-[var(--color-ink-2)]">{{ $registration->domain_area }}</dd>
                        </div>
                    @endif
                    @if($registration->type === 'fellow' && $registration->years_experience)
                        <div>
                            <dt class="label mb-1">Years of experience</dt>
                            <dd class="text-[var(--color-ink-2)]">{{ $registration->years_experience }}</dd>
                        </div>
                    @endif
                    @if($registration->type === 'fellow' && count($domains) > 0)
                        <div class="sm:col-span-2">
                            <dt class="label mb-1">Domain areas</dt>
                            <dd class="flex flex-wrap gap-2 mt-1">
                                @foreach($domains as $domain)
                                    <span class="pill pill-mute">{{ $domain }}</span>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                    <div class="sm:col-span-2">
                        <dt class="label mb-1">Submitted</dt>
                        <dd class="text-[var(--color-mute)]">{{ $sentAt->format('d M Y · h:i A') }}</dd>
                    </div>
                </dl>

                <hr class="my-5 border-[var(--color-line)]">

                <div class="label mb-2">Motivation</div>
                <div class="whitespace-pre-line text-[var(--color-ink)] leading-relaxed">{{ $registration->motivation }}</div>
            </div>
        </div>

        <div class="space-y-4">
            @if($user)
                <div class="card p-5">
                    <div class="label mb-3">Account</div>
                    <div class="text-sm text-[var(--color-ink-2)] space-y-2">
                        <div>{{ $user->fname }} {{ $user->lname }}</div>
                        <div class="text-xs text-[var(--color-mute)] break-all">{{ $user->email }}</div>
                        <div class="text-xs text-[var(--color-mute)]">{{ '@' . $user->username }}</div>
                        @if($user->is_active)
                            <span class="pill pill-leaf">Login active</span>
                        @else
                            <span class="pill pill-clay">Login pending approval</span>
                        @endif
                    </div>
                    <p class="text-xs text-[var(--color-mute)] mt-3">Accepting the application activates this account so the applicant can sign in.</p>
                </div>
            @endif

            <div class="card p-5">
                <div class="label mb-3">Quick actions</div>
                <div class="flex flex-col gap-2">
                    <form method="POST" action="{{ route('admin.registrations.updateStatus', $registration->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="btn btn-primary w-full btn-sm" @disabled($registration->status === 'accepted')>
                            <i class="bi bi-check-lg"></i>
                            Approve
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.registrations.updateStatus', $registration->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-ghost w-full btn-sm" @disabled($registration->status === 'rejected')>
                            <i class="bi bi-x-lg"></i>
                            Reject
                        </button>
                    </form>
                </div>
            </div>

            <div class="card p-5">
                <div class="label mb-3">Update status</div>
                <form method="POST" action="{{ route('admin.registrations.updateStatus', $registration->id) }}" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <select name="status" class="select w-full">
                        <option value="new" @selected($registration->status === 'new')>New</option>
                        <option value="reviewed" @selected($registration->status === 'reviewed')>Reviewed</option>
                        <option value="accepted" @selected($registration->status === 'accepted')>Accepted</option>
                        <option value="rejected" @selected($registration->status === 'rejected')>Rejected</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-full btn-sm">Save status</button>
                </form>
            </div>

            <div class="card p-5">
                <form method="POST" action="{{ route('admin.registrations.destroy', $registration->id) }}" data-confirm="Delete this application permanently?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full btn-sm">
                        <i class="bi bi-trash"></i>
                        Delete application
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
