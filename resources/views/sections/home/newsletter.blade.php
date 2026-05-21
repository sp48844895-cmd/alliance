@php $s = $pageSections['cta'] ?? []; @endphp
<section class="cta home-newsletter" id="newsletter" aria-labelledby="newsletter-h">
@include('sections.defaults.home.newsletter', ['s' => $s])
</section>
