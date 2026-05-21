@php $s = $pageSections['vision_mission'] ?? []; @endphp
<section class="ab-vm container-x" aria-labelledby="ab-vm-h">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.about.vision_mission')
@endif
</section>
