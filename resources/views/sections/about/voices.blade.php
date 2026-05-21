@php $s = $pageSections['voices'] ?? []; @endphp
<section class="st-voices" id="st-voices" aria-labelledby="st-voices-h">
@if(!empty($s['html']))
{!! $s['html'] !!}
@else
@include('sections.defaults.about.voices')
@endif
</section>
