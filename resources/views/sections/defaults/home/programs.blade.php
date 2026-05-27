<div class="container-x">
    <div class="programs-head">
      <div>
        <span class="chapter"><b>03</b> · Programs &amp; Initiatives</span>
        <h2 id="programs-h" data-aos="fade-up">Programs &amp; Initiatives</h2>
      </div>
      <p data-aos="fade-up" data-aos-delay="120">
        A focused view of flagship SBC initiatives across Chhattisgarh — from youth volunteer networks to local learning resources and community led campaigns.
      </p>
    </div>

    @php
      $legacyBase = rtrim((string) config('media.legacy_base', ''), '/');
      $defaults = [
        ['title' => 'Bapi Na Uwat', 'description' => 'Bapi Na Uwat is an innovative community-led SBC initiative launched in Dantewada by the district administration and UNICEF to reduce malnutrition and improve health behaviours in tribal communities.', 'image' => $legacyBase.'/images/home/1.jpg', 'accent' => 'grad'],
        ['title' => 'Yuvoday', 'description' => 'Yuvoday is a youth-led volunteer movement launched in Chhattisgarh with support from district administrations and UNICEF to strengthen community participation and behaviour change.', 'image' => $legacyBase.'/images/home/2.jpg', 'accent' => 'orange'],
        ['title' => 'BijaDuteer', 'description' => 'BijaDuteer is a youth volunteer initiative in Bijapur supported by the District Administration, UNICEF, and Chhattisgarh Agricon Samiti.', 'image' => $legacyBase.'/images/home/3.jpg', 'accent' => 'black'],
        ['title' => 'JAY HO!', 'description' => 'JAY HO, the Jashpur Alliance of Youth for Hope and Opportunity, is a youth empowerment initiative in Jashpur launched by the District Administration and UNICEF.', 'image' => $legacyBase.'/images/home/4.jpg', 'accent' => 'grad'],
        ['title' => 'Learning Corners', 'description' => 'The Learning Corner is a shared resource space under Alliance for Behaviour Change where IEC materials, training modules, toolkits, and campaign resources are made accessible.', 'image' => $legacyBase.'/images/home/5.jpg', 'accent' => 'orange'],
      ];
    @endphp

    <div class="program-flip-grid">
      @foreach ($defaults as $index => $item)
        <x-section.program-flip-card
          :title="$item['title']"
          :description="$item['description']"
          :image-url="$item['image']"
          :accent="$item['accent']"
          :delay="$index * 80"
        />
      @endforeach
    </div>

    <div class="programs-explore" data-aos="fade-up">
      <a href="{{ route('programs') }}" class="btn btn-primary programs-explore__btn">
        Explore More
        <span class="arrow" aria-hidden="true">→</span>
      </a>
    </div>
</div>
