<?php

$img = static fn (string $file): string => '/assets/img/about/approach/'.$file;

$steps = [
    ['num' => '01', 'title' => 'Understand Local Realities', 'text' => 'Understanding local challenges, beliefs, and community needs before designing any communication.', 'file' => 'step-01-understand-local-realities.png', 'alt' => 'Team members talking with community members in a rural setting'],
    ['num' => '02', 'title' => 'User Insights', 'text' => 'Using research and real-world insights—including mobile data collection—to design communication that responds to lived experience.', 'file' => 'step-02-user-insights.png', 'alt' => 'Community members sharing insights using mobile phones'],
    ['num' => '03', 'title' => 'Create Local Messages', 'text' => 'Creating culturally relevant messages, wall art, and local design so communication feels relatable in every space.', 'file' => 'step-03-create-local-messages.png', 'alt' => 'Local campaign messaging and community-designed communication materials'],
    ['num' => '04', 'title' => 'Build People Together', 'text' => 'Engaging communities, frontline workers, youth, media, and policymakers together through group meetings and shared platforms.', 'file' => 'step-04-build-people-together.png', 'alt' => 'Community group discussion during a collaborative workshop'],
    ['num' => '05', 'title' => 'Building Lasting Change', 'text' => 'Promoting lasting behaviour change through participation, trust, and shared ownership—reaching families and children across Chhattisgarh.', 'file' => 'step-05-lasting-change.jpg', 'alt' => 'Mother and child representing healthier futures through community-led change'],
];

$items = '';
foreach ($steps as $step) {
    $items .= '
  <li class="ab-app-step" data-aos="fade-up">
    <div class="ab-app-step-pin" aria-hidden="true">
      <span class="ab-app-step-num">'.$step['num'].'</span>
    </div>
    <article class="ab-app-step-panel">
      <figure class="ab-app-step-fig">
        <img src="'.$img($step['file']).'" alt="'.htmlspecialchars($step['alt'], ENT_QUOTES, 'UTF-8').'" width="640" height="480" loading="lazy" decoding="async" />
      </figure>
      <div class="ab-app-step-body">
        <h3 class="ab-app-step-title">'.$step['title'].'</h3>
        <p class="ab-app-step-text">'.$step['text'].'</p>
      </div>
    </article>
  </li>';
}

return '<div class="ab-app-head">
  <span class="chapter"><b>04</b> · How We Work</span>
  <h2 id="ab-app-h" data-aos="fade-up">Grounded in people, place and <em>trust.</em></h2>
  <p class="ab-app-sub" data-aos="fade-up" data-aos-delay="100">
    ChhattisgarhABC designs communication through local understanding, evidence, culture and collective participation.
  </p>
</div>

<ol class="ab-app-steps">'.$items.'
</ol>';
