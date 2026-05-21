@php $s = $pageSections['filters'] ?? []; @endphp
<section class="cmp-filters" aria-label="Filter campaigns by theme and district">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.campaigns.filters')
@endif
</section>
