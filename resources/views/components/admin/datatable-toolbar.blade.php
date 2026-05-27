@props([
    'title' => null,
    'hint' => null,
])

@if ($title || $hint || isset($actions))
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between mb-5">
        @if ($title || $hint)
            <div>
                @if ($title)
                    <h2 class="font-display text-base text-[var(--color-ink-2)]">{{ $title }}</h2>
                @endif
                @if ($hint)
                    <p class="text-sm text-[var(--color-mute)] mt-0.5">{{ $hint }}</p>
                @endif
            </div>
        @endif
        @if (isset($actions))
            <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
        @endif
    </div>
@endif
