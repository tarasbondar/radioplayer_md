@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="category-slider">
            <div class="container">
                <div class="category-slider__header">
                    <h2 class="h2">{{ __('client.favorites') }}</h2>
                </div>

                <div class="category-slider__wrapper">
                    <div class="swiper" data-category-slider>
                        <div class="swiper-wrapper">
                            {{--{{#times 12}}--}}
                            <div class="swiper-slide">
                                <div class="item">
                                    <div class="logo">
                                        <img class="logo__bg" srcset="/img/radio-logo.png 1x, /img/radio-logo@2x.png 2x" src="/img/radio-logo.png" width="100%" alt="" loading="lazy">
                                        <img class="logo__img" srcset="/img/radio-logo.png 1x, /img/radio-logo@2x.png 2x" src="/img/radio-logo.png" width="100%" alt="" loading="lazy">
                                    </div>
                                    <h3 class="x-small item__title">Radio Relax Instrumental</h3>
                                    <button class="item__link" type="button" aria-label="{{ __('client.playStation') }}"></button>
                                    <button class="item__favourites-btn active" type="button" aria-label="{{ __('client.addToFavourites') }}">
                                        <span class="item__favourites-btn__inner">
                                            <svg class="icon" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            {{--{{/times}}--}}
                        </div>
                    </div>

                    <div class="swiper-button swiper-button-prev" data-category-slider-prev>
                        <svg class="icon">
                            <use href="/img/sprite.svg#chevron-left"></use>
                        </svg>
                    </div>
                    <div class="swiper-button swiper-button-next" data-category-slider-next>
                        <svg class="icon">
                            <use href="/img/sprite.svg#chevron-right"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <section class="items-list">
            <div class="container">
                <div class="tab-filters">
                    <ul class="tab-filters__list">
                        <li class="tab-filters__item">
                            <button class="btn btn_switcher btn_large active" type="button">Все</button>
                        </li>
                        <li class="tab-filters__item">
                            <button class="btn btn_switcher btn_large" type="button">Недавние</button>
                        </li>
                        <li class="tab-filters__item">
                            <button class="btn btn_switcher btn_large" type="button">Сезонные</button>
                        </li>
                        <li class="tab-filters__item">
                            <button class="btn btn_switcher btn_large" type="button">Подкасты</button>
                        </li>
                    </ul>

                    <ul class="tab-filters__list">
                        <li class="tab-filters__item">
                            <button class="btn btn_switcher btn_large" type="button">Рок</button>
                        </li>
                        <li class="tab-filters__item">
                            <button class="btn btn_switcher btn_large" type="button">Джаз</button>
                        </li>
                        <li class="tab-filters__item">
                            <button class="btn btn_switcher btn_large" type="button">Поп</button>
                        </li>
                        <li class="tab-filters__item">
                            <button class="btn btn_switcher btn_large" type="button">Диско</button>
                        </li>
                        <li class="tab-filters__item">
                            <button class="btn btn_switcher btn_large" type="button">Фанк</button>
                        </li>
                    </ul>
                </div>

                <div class="items-list__grid">
                    {{--{{#times 12}}--}}
                        <div class="item">
                            <div class="logo">
                                <img class="logo__bg" srcset="/img/radio-logo.png 1x, /img/radio-logo@2x.png 2x" src="/img/radio-logo.png" width="100%" alt="" loading="lazy">
                                <img class="logo__img" srcset="/img/radio-logo.png 1x, /img/radio-logo@2x.png 2x" src="/img/radio-logo.png" width="100%" alt="" loading="lazy">
                            </div>
                            <h3 class="x-small item__title">Radio Relax Instrumental</h3>
                            <button class="item__link" type="button" aria-label="{{ __('client.playStation') }}"></button>
                            <button class="item__favourites-btn" type="button" aria-label="{{ __('client.addToFavourites') }}">
                                <span class="item__favourites-btn__inner">
                                    <svg class="icon" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    {{--{{/times}}--}}
                </div>
            </div>
        </section>
    </main>

@endsection
