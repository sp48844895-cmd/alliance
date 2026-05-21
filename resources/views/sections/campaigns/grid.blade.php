@php $s = $pageSections['grid'] ?? []; @endphp
<section class="cmp-grid container-x" id="cmp-grid" aria-labelledby="cmp-grid-h">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.campaigns.grid')
@endif
</section>
