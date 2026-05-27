@props([
    'empty' => false,
    'emptyIcon' => 'bi-inbox',
    'emptyTitle' => 'Nothing here yet',
    'emptyText' => null,
])

@if ($empty)
    <div class="card p-10 text-center">
        <i class="bi {{ $emptyIcon }} text-3xl text-[var(--color-mute-2)]"></i>
        <div class="font-display text-lg text-[var(--color-ink-2)] mt-3">{{ $emptyTitle }}</div>
        @if ($emptyText)
            <p class="text-sm text-[var(--color-mute)] mt-1">{{ $emptyText }}</p>
        @endif
        @if (isset($emptyAction))
            <div class="mt-4">{{ $emptyAction }}</div>
        @endif
    </div>
@else
    <div {{ $attributes->merge(['class' => 'card overflow-hidden admin-datatable-wrap']) }}>
        <div class="w-full max-w-full overflow-hidden">
            {{ $slot }}
        </div>
    </div>
@endif
