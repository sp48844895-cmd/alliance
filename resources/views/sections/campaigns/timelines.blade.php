@php $s = $pageSections['timelines'] ?? []; @endphp
<section class="cmp-tl-section" aria-labelledby="cmp-tl-h">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.campaigns.timelines')
@endif
</section>
