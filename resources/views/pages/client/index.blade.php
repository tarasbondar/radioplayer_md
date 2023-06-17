@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="category-slider">
            <div class="container">

                <div class="category-slider__header">
                    <h2 class="h2">{{ __('client.favorites') }}</h2>
                </div>

                <div class="no-favorite @if(count($fav_stations) !== 0) d-none @endif">
                    {{ __('client.noFavStations') }}
                </div>

                <div class="category-slider__wrapper @if(count($fav_stations) === 0) d-none @endif">
                    <div class="swiper" data-category-slider>
                        <div class="swiper-wrapper favorites-container">
                            @foreach ($fav_stations as $station_id)
                                @include('partials.station-card', ['station' => $stations[$station_id]])
                            @endforeach
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
                            <button id="search-all" class="btn btn_switcher btn_large {{ !isset($_GET['category_id']) ? 'active' : '' }}" type="button" value="0"> {{ __('stationcategories.all') }} </button>
                        </li>
                        @foreach($categories as $c)
                            <li class="tab-filters__item">
                                <button class="btn btn_switcher btn_large button-category {{ (isset($_GET['category_id']) && $_GET['category_id'] == $c['id']) ? 'active' : '' }}" type="button" value="{{ $c['id'] }}"> {{ $c->getTranslation('title') }} </button>
                            </li>
                        @endforeach
                    </ul>

                    <ul class="tab-filters__list">
                        @foreach($tags as $t)
                            <li class="tab-filters__item">
                                <button class="btn btn_switcher btn_large button-tag {{ (isset($_GET['tag_id']) && $_GET['tag_id'] == $t['id']) ? 'active' : '' }}" type="button" value=" {{$t['id']}}"> {{ $t->getTranslation('title') }} </button>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="items-list__grid stations-container">
                    @foreach($stations as $station)
                        @include('partials.station-card', ['station' => $station])
                    @endforeach
                </div>
            </div>
        </section>
    </main>
@endsection
