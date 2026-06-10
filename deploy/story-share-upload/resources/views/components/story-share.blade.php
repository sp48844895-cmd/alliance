@props([
    'story' => null,
    'url' => '',
    'title' => '',
    'description' => '',
    'hashtags' => '#SBCMatters',
])

<x-story-share-links
    :story="$story"
    :url="$url"
    :title="$title"
    :description="$description"
    :hashtags="$hashtags"
    compact
/>
