import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

document.addEventListener('DOMContentLoaded', function () {
    const progressBarThumb = document.querySelector('.custom-slider-thumb');

    if (!document.querySelector('.fashionCategorySlider')) return;

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
            init: function () { updateProgressBar(this); },
            slideChange: function () { updateProgressBar(this); },
            slideChangeTransitionEnd: function () { updateProgressBar(this); },
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
