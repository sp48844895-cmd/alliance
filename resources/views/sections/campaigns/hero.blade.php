@php $s = $pageSections['hero'] ?? []; @endphp
<section class="cmp-hero" aria-labelledby="cmp-hero-h">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.campaigns.hero')
@endif
</section>
