<section class="container-x section" aria-labelledby="events-home-h">
    <x-section.home-events
        :section="$pageSections['events'] ?? []"
        :events="$homeRecentEvents ?? []"
    />
</section>
