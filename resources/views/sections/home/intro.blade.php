@php $s = $pageSections['intro'] ?? []; @endphp
<section class="intro container-x" aria-labelledby="intro-h">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.home.intro')
@endif
</section>
