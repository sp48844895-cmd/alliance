@extends('layouts.intern')

@section('title', 'Dashboard')
@section('page_title', 'My dashboard')

@section('breadcrumb')
    <span>Dashboard</span>
@endsection

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-sm text-[var(--color-mute)]">
            Welcome back, <span class="font-semibold text-[var(--color-ink-2)]">{{ auth()->user()->fname ?? 'Intern' }}</span>.
            Log your daily work below, or share a story with the community.
        </p>
        <a href="{{ route('author.stories.create') }}" class="btn btn-primary btn-sm shrink-0">
            <i class="bi bi-journal-text"></i>
            Post a story
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="stat">
            <div class="stat-label">Total entries</div>
            <div class="stat-num mt-2">{{ number_format($logCount) }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Hours logged</div>
            <div class="stat-num mt-2">{{ number_format($totalHours, 1) }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Today</div>
            <div class="stat-num mt-2 text-[var(--color-clay-600)]">{{ now()->format('d M Y') }}</div>
        </div>
    </div>

    <div class="card p-5 lg:p-6 mb-8">
        <h2 class="font-display text-lg text-[var(--color-ink-2)] mb-1">Work log</h2>
        <p class="text-xs text-[var(--color-mute)] mb-5">Record what you worked on today — tasks, hours and any challenges.</p>

        <form method="POST" action="{{ route('intern.work-log.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="log_date">Date</label>
                    <input type="date" id="log_date" name="log_date" class="input" value="{{ old('log_date', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required>
                    @error('log_date')
                        <p class="err">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label" for="hours_worked">Hours worked</label>
                    <input type="number" id="hours_worked" name="hours_worked" class="input" step="0.25" min="0.25" max="24" value="{{ old('hours_worked') }}" placeholder="e.g. 6" required>
                    @error('hours_worked')
                        <p class="err">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="label" for="tasks_done">Tasks done</label>
                <textarea id="tasks_done" name="tasks_done" class="input min-h-[120px]" rows="5" placeholder="What did you work on today?" required>{{ old('tasks_done') }}</textarea>
                @error('tasks_done')
                    <p class="err">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label" for="notes">Challenges / notes <span class="text-[var(--color-mute)] font-normal">(optional)</span></label>
                <textarea id="notes" name="notes" class="input min-h-[80px]" rows="3" placeholder="Any blockers, learnings or follow-ups">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="err">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Add entry
            </button>
        </form>
    </div>

    <div class="card p-5 lg:p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-display text-lg text-[var(--color-ink-2)]">Past work logs</h2>
                <p class="text-xs text-[var(--color-mute)] mt-0.5">Your submitted entries, newest first</p>
            </div>
        </div>

        @if($logs->isEmpty())
            <p class="text-sm text-[var(--color-mute)]">No work log entries yet. Add your first entry above.</p>
        @else
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Tasks done</th>
                            <th>Hours</th>
                            <th>Notes</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td class="whitespace-nowrap font-semibold text-[var(--color-ink-2)]">
                                    {{ \Illuminate\Support\Carbon::parse($log->log_date)->format('d M Y') }}
                                </td>
                                <td class="max-w-md">
                                    <span class="line-clamp-3 text-sm text-[var(--color-ink-soft)]">{{ $log->tasks_done }}</span>
                                </td>
                                <td class="whitespace-nowrap">{{ number_format((float) $log->hours_worked, 2) }}</td>
                                <td class="max-w-xs text-sm text-[var(--color-mute)]">
                                    @if($log->notes)
                                        <span class="line-clamp-2">{{ $log->notes }}</span>
                                    @else
                                        <span class="text-[var(--color-mute-2)]">—</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap text-xs text-[var(--color-mute)]">
                                    {{ \Illuminate\Support\Carbon::parse($log->created_at)->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
