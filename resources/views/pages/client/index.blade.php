@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="category-slider">
            <div class="container">

                <div class="category-slider__header">
                    <h2 class="h2">{{ __('client.favorites') }}</h2>
                </div>

                <div class="no-favorite @if(count($fav_stations) !== 0) d-none @endif">
                    {{ 'You have no favorite radiostations' }}
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
                            <button id="search-all" class="btn btn_switcher btn_large {{ !isset($_GET['category_id']) ? 'active' : '' }}" type="button" value="0"> All </button>
                        </li>
                        @foreach($categories as $c)
                            <li class="tab-filters__item">
                                <button class="btn btn_switcher btn_large button-category {{ (isset($_GET['category_id']) && $_GET['category_id'] == $c['id']) ? 'active' : '' }}" type="button" value="{{ $c['id'] }}"> {{$c['key']}} </button>
                            </li>
                        @endforeach
                    </ul>

                    <ul class="tab-filters__list">
                        @foreach($tags as $t)
                            <li class="tab-filters__item">
                                <button class="btn btn_switcher btn_large button-tag {{ (isset($_GET['tag_id']) && $_GET['tag_id'] == $t['id']) ? 'active' : '' }}" type="button" value=" {{$t['id']}}"> {{$t['key']}} </button>
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

    <script>

        (function(){

            $(document).on('click', '#search-all', function () {
                $('.button-category.active').removeClass('active');
                $('.button-tag.active').removeClass('active');
                $(this).add('active');
                stations_filters();
            });

            $(document).on('click', '.button-category:not(.active)', function() {
                $('.button-category').removeClass('active');
                $('#search-all').removeClass('active');
                $(this).addClass('active');
                stations_filters();
            });

            $(document).on('click', '.button-tag:not(.active)', function() {
                $('.button-tag').removeClass('active');
                $(this).addClass('active');
                stations_filters();
            });

            function stations_filters() {

                let category_id = $('.button-category.active').val();

                let tag_id = $('.button-tag.active').val();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/update-stations',
                    data: {'category_id': category_id, 'tag_id': tag_id},
                    success: function(response) {
                        $('.stations-container').html(response);
                    }
                });

            }

            $(document).on('click', '.fav-station', function(){
                let station_id = $(this).val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'GET',
                    url: '/favorite-station/' + station_id,
                    success: function(response) {
                        if (response.action === 'added') {
                            if ($('.favorites-container .swiper-slide').length === 0) {
                                $('.no-favorite').addClass('d-none');
                                $('.category-slider__wrapper').removeClass('d-none');
                                $('.favorites-container').html(response.output);
                            } else {
                                $('.swiper-wrapper').append(response.output);
                            }
                            $('.fav-station[value="'+response.id+'"]').addClass('active');
                        }
                        if (response.action === 'deleted') {
                            $('.fav-station[value="'+response.id+'"]').removeClass('active');
                            $('.favorites-container > .station-' + response.id).remove();
                            if ($('.favorites-container .swiper-slide').length === 0) {
                                $('.category-slider__wrapper').addClass('d-none');
                                $('.no-favorite').removeClass('d-none');
                            }
                        }
                    }
                })
            });

        })(jQuery)

    </script>

@endsection
