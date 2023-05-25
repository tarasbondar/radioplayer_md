@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="podcast">
            <div class="container">
                <div class="podcast__wrap">
                    <div class="podcast__info">
                        <div class="podcast__picture">
                            <img class="podcast__bg" srcset="/img/podcast-bg.png 1x, /img/podcast-bg@2x.png 2x" src="/img/podcast-bg.png" width="100%" alt="" loading="lazy">
                            <img class="podcast__img"
                                 srcset="{{ !empty($podcast['image']) ? '/uploads/podcasts_images/' . $podcast['image'] : "/img/podcast-placeholder.png"}} 1x,
                                 {{ !empty($podcast['image']) ? '/uploads/podcasts_images/' . $podcast['image'] : "/img/podcast-placeholder.png"}} 2x"
                                 src="{{ !empty($podcast['image']) ? '/uploads/podcasts_images/' . $podcast['image'] : "/img/podcast-placeholder.png"}}"
                                 width="100%" alt="" loading="lazy">
                        </div>
                        <div class="podcast__info-text">
                            <span class="podcast__descr">{{ $podcast['username'] }}</span>
                            <span class="podcast__descr">{{ $podcast['description'] }}</span>
                            <strong class="podcast__title">{{ $podcast['name'] }}</strong>
                            <div class="button-container">
                                @if ($action == 'edit')
                                    <a href="/edit-podcast/{{$podcast['id']}}" class="btn btn_default btn_secondary podcast__btn">
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16 1.99981C16.2626 1.73717 16.5744 1.52883 16.9176 1.38669C17.2608 1.24455 17.6286 1.17139 18 1.17139C18.3714 1.17139 18.7392 1.24455 19.0824 1.38669C19.4256 1.52883 19.7374 1.73717 20 1.99981C20.2626 2.26246 20.471 2.57426 20.6131 2.91742C20.7553 3.26058 20.8284 3.62838 20.8284 3.99981C20.8284 4.37125 20.7553 4.73905 20.6131 5.08221C20.471 5.42537 20.2626 5.73717 20 5.99981L6.5 19.4998L1 20.9998L2.5 15.4998L16 1.99981Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Редактировать
                                    </a>
                                @elseif ($action == 'unsub')
                                    @include('partials.unsub-button')
                                @else
                                    @include('partials.sub-button')
                                @endif
                            </div>
                        </div>
                    </div>

                    <ul class="podcast__list">
                        @if(!empty($episodes))
                            @foreach($episodes as $episode)
                                @include('partials.episode-card')
                            @endforeach
                        @else
                            <strong class="podcast__title">У вас нет ни одного выпуска</strong>
                            <p class="podcast__text">Загрузите фалы выпусков</p>
                            <a href="/create-episode/{{$podcast['id']}}" class="btn btn_default btn_primary podcast__add">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.5 22C18.0228 22 22.5 17.5228 22.5 12C22.5 6.47715 18.0228 2 12.5 2C6.97715 2 2.5 6.47715 2.5 12C2.5 17.5228 6.97715 22 12.5 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.5 8V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.5 12H16.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Добавить выпуск
                            </a>
                        @endif
                    </ul>
                </div>
            </div>
            @if ($action == 'edit')
                <a href="/create-episode/{{$podcast['id']}}" class="btn btn_ico btn_ico-accent page-btn _playing" aria-label="Добавить">
                    <svg class="icon">
                        <use href="/img/sprite.svg#plus"></use>
                    </svg>
                </a>
            @endif
        </section>
    </main>


    <script>

        (function(){
            $(document).on('click', '.download-episode', function(){
                let id = $(this).attr('data-id');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/download-episode',
                    data: {id: id},
                    success: function(response) {

                    }
                })
            });

            $(document).on('click', '.listen-later', function () {
                let id = $(this).attr('data-id');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/queue-episode',
                    data: {id: id},
                    success: function(response) {

                    }
                })
            });

            //delete-episode/$episode['id']
            $(document).on('click', '.delete-episode', function () {
                let id = $(this).attr('data-id');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/delete-episode',
                    data: {id: id},
                    success: function (response) {

                    }
                })
            });


            $(document).on('click', '.subscribe-to', function () {
                let button = $(this);
                let classes = button.attr('class').split(" ");
                let podcast_id = 0;
                $.each(classes, function (k, v) {
                    if (v.search('podcast-') >= 0) {
                        podcast_id = v.split("-")[1];
                    }
                });

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/subscribe-to',
                    data: {id: podcast_id },
                    success: function(response){
                        $('.button-container').html(response);
                    }
                })
            })
        })(jQuery)

    </script>

@endsection
