@php
    $join = $pageSections['join'] ?? [];
    $joinOptions = $join['options'] ?? [];
@endphp

<section class="gi-join" id="gi-join" aria-labelledby="gi-join-h">
    <div class="container-x">
        <div class="gi-join-head" data-aos="fade-up"></div>

        <div class="gi-join-grid">
            @foreach ($joinOptions as $option)
                <article class="gi-join-card" data-aos="fade-up">
                    <span class="gi-join-card__icon">{{ $option['icon'] }}</span>
                    <h3>{{ $option['title'] }}</h3>
                    <p>{{ $option['detail'] }}</p>
                    <ul>
                        @foreach ($option['items'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                    <a class="btn btn-ghost" href="{{ route('contact') }}">Join as {{ $option['title'] }}</a>
                </article>
            @endforeach
        </div>
    </div>
</section>
