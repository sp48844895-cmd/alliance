@extends('layouts.fellow')

@section('title', 'Dashboard')
@section('page_title', 'My fellowship')

@section('breadcrumb')
    <span>Dashboard</span>
@endsection

@section('content')
    <div class="mb-6">
        <p class="text-sm text-[var(--color-mute)]">
            Welcome, <span class="font-semibold text-[var(--color-ink-2)]">{{ auth()->user()->fname ?? 'Fellow' }}</span>.
            Your fellowship application has been approved. Programme updates and assignments will appear here as they are shared by the coordination team.
        </p>
    </div>

    @if($registration)
        <div class="card p-5 lg:p-6 max-w-2xl">
            <h2 class="font-display text-lg text-[var(--color-ink-2)] mb-4">Your application</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="label mb-1">Email</dt>
                    <dd class="text-[var(--color-ink-2)] break-all">{{ $registration->email }}</dd>
                </div>
                <div>
                    <dt class="label mb-1">Phone</dt>
                    <dd class="text-[var(--color-ink-2)]">{{ $registration->phone }}</dd>
                </div>
                @if($registration->institution)
                    <div class="sm:col-span-2">
                        <dt class="label mb-1">Organisation</dt>
                        <dd class="text-[var(--color-ink-2)]">{{ $registration->institution }}</dd>
                    </div>
                @endif
                @if($registration->years_experience)
                    <div>
                        <dt class="label mb-1">Experience</dt>
                        <dd class="text-[var(--color-ink-2)]">{{ $registration->years_experience }}</dd>
                    </div>
                @endif
                @if(count($domains) > 0)
                    <div class="sm:col-span-2">
                        <dt class="label mb-1">Domain areas</dt>
                        <dd class="flex flex-wrap gap-2 mt-1">
                            @foreach($domains as $domain)
                                <span class="pill pill-mute">{{ $domain }}</span>
                            @endforeach
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    @endif

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('get-involved') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-compass"></i>
            Get involved
        </a>
        <a href="{{ route('contact') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-envelope"></i>
            Contact alliance
        </a>
    </div>
@endsection
