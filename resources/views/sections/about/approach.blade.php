@php $s = $pageSections['approach'] ?? []; @endphp
<section class="ab-approach container-x" aria-labelledby="ab-app-h">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.about.approach')
@endif
</section>
