@props([
    'chapterNum' => '',
    'chapterLabel' => '',
    'headingHtml' => '',
    'sideText' => '',
    'headingId' => null,
    'wrapperClass' => 'champions-head',
])

<div class="{{ $wrapperClass }}">
    <div>
        @if ($chapterNum !== '' || $chapterLabel !== '')
            <span class="chapter"><b>{{ $chapterNum }}</b>@if ($chapterLabel !== '') · {{ $chapterLabel }}@endif</span>
        @endif
        @if ($headingHtml !== '')
            <h2 @if ($headingId) id="{{ $headingId }}" @endif class="type-section-title">{!! $headingHtml !!}</h2>
        @endif
    </div>
    @if ($sideText !== '')
        <p class="head-side">{{ $sideText }}</p>
    @endif
</div>
