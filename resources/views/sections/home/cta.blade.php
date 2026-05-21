@php $s = $pageSections['cta'] ?? []; @endphp
<section class="cta" aria-labelledby="cta-h">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.home.cta')
@endif
</section>
