@props([
    'action',
    'active' => false,
    'activeTitle' => 'Deactivate',
    'inactiveTitle' => 'Activate',
    'variant' => 'toggle',
])

<form method="POST" action="{{ $action }}" data-admin-ajax data-ajax-status-target="[data-status-cell]" {{ $attributes->merge(['class' => 'inline']) }}>
    @csrf
    <button type="submit" class="btn btn-ghost btn-sm" title="{{ $active ? $activeTitle : $inactiveTitle }}">
        @if ($variant === 'publish')
            <i class="bi {{ $active ? 'bi-check-circle-fill text-[var(--color-leaf)]' : 'bi-x-circle-fill text-[var(--color-flame)]' }}"></i>
        @else
            <i class="bi {{ $active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
        @endif
    </button>
</form>
