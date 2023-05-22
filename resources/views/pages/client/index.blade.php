@extends('layouts/client')

@section('content')

    <main class="main">
        @auth
            <section class="category-slider">
                <div class="container">

                    <div class="category-slider__header">
                        <h2 class="h2">Избранные</h2>
                    </div>

                    @if (count($fav_stations) == 0)
                        {{ 'You have no favorite radiostations' }}
                    @else
                        <div class="category-slider__wrapper">
                            <div class="swiper" data-category-slider>
                                <div class="swiper-wrapper favorites-container">
                                    @foreach ($fav_stations as $station)
                                        @include('partials.station-card')
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
                    @endif
                </div>
            </section>
        @endauth

        <section class="items-list">
            <div class="container">
                <div class="tab-filters">
                    <ul class="tab-filters__list">
                        <li class="tab-filters__item">
                            <button class="btn btn_switcher btn_large {{ !isset($_GET['category_id']) ? 'active' : '' }} button-category" type="button" value="0"> All </button>
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
                        @include('partials.station-card')
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <script>

        (function(){

            $(document).on('click', '.button-category:not(.active)', function() {
                $('.button-category').removeClass('active');
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
                        if (response.action === 'added') { //refresh entire list?
                            if ($('.favorites-container .swiper-slide').length === 0) {
                                $('.favorites-container').html(response.output);
                            } else {
                                $('.favorites-container .swiper-slide:last').append(response.output);
                            }
                            $('#station-'+response.id + ' .fav-station').addClass('active');
                        }
                        if (response.action === 'deleted') {
                            $('#station-'+response.id).remove();
                            $('#station-'+response.id + ' .fav-station').removeClass('active');
                        }
                    }
                })
            });

        })(jQuery)

    </script>

@endsection
