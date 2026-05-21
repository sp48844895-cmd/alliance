<section class="programs-section" id="programs-initiatives" aria-labelledby="programs-h">
    <x-section.home-programs
        :section="$pageSections['programs'] ?? []"
        :programs="$homePrograms ?? null"
    />
</section>
