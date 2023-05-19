import Swiper, { Navigation } from 'swiper';

import 'swiper/css';
import 'swiper/css/navigation';

export function categorySlider() {
    const categorySliderEl = document.querySelector('[data-category-slider]');

    if (categorySliderEl) {
        const catSlider = new Swiper(categorySliderEl, {
            modules: [Navigation],

            slidesPerView: 3.718,
            slidesOffsetBefore: 20,
            slidesOffsetAfter: 20,
            spaceBetween: 8,
            slidesPerGroup: 3,

            navigation: {
                nextEl: '[data-category-slider-next]',
                prevEl: '[data-category-slider-prev]',
            },

            breakpoints: {
                992: {
                    slidesPerView: 5,
                    slidesOffsetBefore: 0,
                    slidesOffsetAfter: 0,
                    spaceBetween: 24,
                    slidesPerGroup: 4,
                }
            }
        });
    }

}
