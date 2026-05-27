@props([
    'url' => '',
    'title' => '',
    'hashtags' => '#SBCMatters',
])

<button
    type="button"
    class="st-share-btn"
    data-st-share
    data-share-url="{{ $url }}"
    data-share-title="{{ $title }}"
    data-share-hashtags="{{ $hashtags }}"
    aria-label="Share this story"
>
    <span class="st-share-btn__icon" aria-hidden="true">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.59 13.51l6.83 3.98M15.41 6.51l-6.82 3.98"/></svg>
    </span>
    <span class="st-share-btn__label">Share</span>
</button>
