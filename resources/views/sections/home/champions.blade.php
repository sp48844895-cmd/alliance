<section class="champions-section" aria-labelledby="ch-h">
    <x-section.home-champions
        :section="$pageSections['champions'] ?? []"
        :stories="$homeRecentStories ?? []"
    />
</section>
