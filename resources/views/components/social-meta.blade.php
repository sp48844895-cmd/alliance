@props(['meta'])

@php
/** @var \App\Support\SocialMeta $meta */
@endphp

<link rel="canonical" href="{{ $meta->url }}" />
<meta property="og:site_name" content="{{ $meta::SITE_NAME }}" />
<meta property="og:locale" content="en_IN" />
<meta property="og:title" content="{{ $meta->title }}" />
<meta property="og:description" content="{{ $meta->description }}" />
<meta property="og:url" content="{{ $meta->url }}" />
<meta property="og:type" content="{{ $meta->type }}" />
@if ($meta->image !== '')
  <meta property="og:image" content="{{ $meta->image }}" />
  @if (str_starts_with($meta->image, 'https://'))
    <meta property="og:image:secure_url" content="{{ $meta->image }}" />
  @endif
  <meta property="og:image:alt" content="{{ $meta->title }}" />
  @if ($meta->imageWidth)
    <meta property="og:image:width" content="{{ $meta->imageWidth }}" />
  @endif
  @if ($meta->imageHeight)
    <meta property="og:image:height" content="{{ $meta->imageHeight }}" />
  @endif
@endif

<meta name="twitter:card" content="{{ $meta->twitterCard() }}" />
<meta name="twitter:title" content="{{ $meta->title }}" />
<meta name="twitter:description" content="{{ $meta->description }}" />
@if ($meta->image !== '')
  <meta name="twitter:image" content="{{ $meta->image }}" />
  <meta name="twitter:image:alt" content="{{ $meta->title }}" />
@endif
