@php $s = $pageSections['cta'] ?? []; @endphp
<section class="cmp-cta" aria-labelledby="cmp-cta-h">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.campaigns.cta')
@endif
</section>
