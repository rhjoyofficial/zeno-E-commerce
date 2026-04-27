@extends('layouts.app')
@section('title', 'Shopping')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush

@section('content')
    @include('frontend.hero-section')

    @foreach ($homeSections as $section)
        @php $products = $sectionProducts[$section->id] ?? collect(); @endphp
        @if ($section->type === 'new_arrivals')
            @include('frontend.dynamic-new-arrivals', ['section' => $section, 'products' => $products, 'topCategories' => $topCategories])
        @elseif (in_array($section->type, ['mens_fashion', 'womens_fashion', 'kids_fashion']))
            @include('frontend.dynamic-fashion', ['section' => $section, 'products' => $products])
        @endif
        <hr>
    @endforeach
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const progressBarThumb = document.querySelector('.custom-slider-thumb');

            if (progressBarThumb) {
                progressBarThumb.style.width = '0%';
            }

            const fashionCategorySlider = new Swiper('.fashionCategorySlider', {
                slidesPerView: 3,
                spaceBetween: 24,
                loop: false,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 2, spaceBetween: 20 },
                    768: { slidesPerView: 3, spaceBetween: 24 },
                    1024: { slidesPerView: 3, spaceBetween: 24 },
                },
                on: {
                    init: function() { updateProgressBar(this); },
                    slideChange: function() { updateProgressBar(this); },
                    slideChangeTransitionEnd: function() { updateProgressBar(this); },
                },
            });

            function updateProgressBar(swiperInstance) {
                if (!progressBarThumb) return;
                const totalSlides = swiperInstance.slides.length;
                const slidesPerView = swiperInstance.params.slidesPerView;
                const maxIndex = Math.max(0, totalSlides - slidesPerView);
                if (maxIndex === 0) {
                    progressBarThumb.style.width = '100%';
                    return;
                }
                const currentIndex = swiperInstance.activeIndex;
                const consumedSlides = currentIndex + slidesPerView;
                const progressPercentage = (consumedSlides / totalSlides) * 100;
                progressBarThumb.style.width = `${Math.min(100, Math.max(0, progressPercentage))}%`;
            }

            setTimeout(() => { updateProgressBar(fashionCategorySlider); }, 100);
        });
    </script>
@endpush
