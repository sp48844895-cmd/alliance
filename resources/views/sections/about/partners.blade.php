@php $s = $pageSections['partners'] ?? []; @endphp
<section class="ab-partners" aria-labelledby="ab-p-h">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.about.partners')
@endif
</section>
