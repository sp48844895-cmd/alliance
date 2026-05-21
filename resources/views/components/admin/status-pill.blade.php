@props([
    'active' => false,
    'activeLabel' => 'Active',
    'inactiveLabel' => 'Inactive',
    'activeClass' => 'pill-leaf',
    'inactiveClass' => 'pill-flame',
])

<span class="pill {{ $active ? $activeClass : $inactiveClass }}">
    {{ $active ? $activeLabel : $inactiveLabel }}
</span>
