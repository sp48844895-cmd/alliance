@php $s = $pageSections['hub'] ?? []; @endphp
<section class="hub container-x" aria-labelledby="hub-h">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.home.hub')
@endif
</section>
