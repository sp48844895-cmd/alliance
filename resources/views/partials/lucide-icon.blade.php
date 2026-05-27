@php
  $iconClass = $class ?? 'icon-folder';
@endphp
@if (str_starts_with($iconClass, 'bi ') || str_starts_with($iconClass, 'fa-'))
  <i class="{{ $iconClass }}" aria-hidden="true"></i>
@else
  <div class="{{ $iconClass }} lc-lucide" aria-hidden="true"></div>
@endif
