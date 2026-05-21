@php $s = $pageSections['hero'] ?? []; @endphp
<section class="ab-hero" aria-labelledby="ab-hero-h">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.about.hero')
@endif
</section>
