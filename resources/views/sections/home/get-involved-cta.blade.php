@php $s = $pageSections['get_involved_cta'] ?? []; @endphp
<section class="cta home-get-involved" id="home-get-involved" aria-labelledby="get-involved-h">
@include('sections.defaults.home.get-involved-cta', ['s' => $s])
</section>
