@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="podcast">
            <div class="container">
                <div class="podcast__wrap">
                    <a class="btn btn_link small podcast__back" data-ignore href="javascript:void(0);">
                        <svg class="icon">
                            <use href="/img/sprite.svg#chevron-left"></use>
                        </svg>
                        {{ __('app.back') }}
                    </a>
                    <div class="podcast__info">
                        <div class="podcast__picture">
                            <a class="btn btn_small btn_overlay x-small" href="#">
                                <svg class="icon">
                                    <use href="/img/sprite.svg#chevron-left"></use>
                                </svg>
                                {{ __('app.back') }}
                            </a>
                            <img class="podcast__bg" srcset="/img/podcast-bg.png 1x, /img/podcast-bg@2x.png 2x" src="/img/podcast-bg.png" width="100%" alt="" loading="lazy">
                            <img class="podcast__img podcast___img"
                                 srcset="{{ !empty($podcast['image']) ? '/uploads/podcasts_images/' . $podcast['image'] : "/img/podcast-placeholder.png"}} 1x,
                                 {{ !empty($podcast['image']) ? '/uploads/podcasts_images/' . $podcast['image'] : "/img/podcast-placeholder.png"}} 2x"
                                 src="{{ !empty($podcast['image']) ? '/uploads/podcasts_images/' . $podcast['image'] : "/img/podcast-placeholder.png"}}"
                                 width="100%" height="100%" alt="" loading="lazy">
                        </div>
                        <div class="podcast__info-text">
                            <span class="podcast__descr"><a href="/author/{{ $podcast['owner_id'] }}">{{ $podcast['username'] }}</a></span>
                            <strong class="podcast__title">{{ $podcast['name'] }}</strong>

                            @if(strlen($podcast['description']) < 100)
                                <p class="podcast__descr">{!! $podcast['description'] !!}</p>
                            @else
                                <p id="descr-full" class="podcast__descr" hidden="true">
                                    {{ strip_tags($podcast['description']) }}
                                    <button class="btn btn_more" id="less" type="button"><span>{{ __('client.less') }}</span></button>
                                </p>
                                <p id="descr-short" class="podcast__descr">
                                    {{ substr(strip_tags($podcast['description']), 0, 95) }}
                                    <button class="btn btn_more" id="more" type="button"><span>{{ __('client.more') }}</span></button>
                                </p>
                            @endif

                            <div class="button-container">
                                @if ($action == 'edit')
                                    <a href="/edit-podcast/{{$podcast['id']}}" class="btn btn_default btn_secondary podcast__btn">
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16 1.99981C16.2626 1.73717 16.5744 1.52883 16.9176 1.38669C17.2608 1.24455 17.6286 1.17139 18 1.17139C18.3714 1.17139 18.7392 1.24455 19.0824 1.38669C19.4256 1.52883 19.7374 1.73717 20 1.99981C20.2626 2.26246 20.471 2.57426 20.6131 2.91742C20.7553 3.26058 20.8284 3.62838 20.8284 3.99981C20.8284 4.37125 20.7553 4.73905 20.6131 5.08221C20.471 5.42537 20.2626 5.73717 20 5.99981L6.5 19.4998L1 20.9998L2.5 15.4998L16 1.99981Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        {{ __('app.edit') }}
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
                            @if($action == 'edit')
                                <strong class="podcast__title">{{ __('client.noEpisodesNotice') }}</strong>
                                <p class="podcast__text">{{ __('client.noEpisodesDecription') }}</p>
                                <a href="/create-episode/{{$podcast['id']}}" class="btn btn_default btn_primary podcast__add">
                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.5 22C18.0228 22 22.5 17.5228 22.5 12C22.5 6.47715 18.0228 2 12.5 2C6.97715 2 2.5 6.47715 2.5 12C2.5 17.5228 6.97715 22 12.5 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12.5 8V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M8.5 12H16.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    {{ __('client.addEpisode') }}
                                </a>
                            @else
                                <strong class="podcast__title">{{ __('client.noAvailablePodcasts') }}</strong>
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
            @if ($action == 'edit')
                <a href="/create-episode/{{$podcast['id']}}" class="btn btn_ico btn_ico-accent page-btn _playing" aria-label="{{ __('app.add') }}">
                    <svg class="icon">
                        <use href="/img/sprite.svg#plus"></use>
                    </svg>
                </a>
            @endif
        </section>
    </main>

    <script>

        (function(){

            $(document).on('click', '#more', function(){
                $('#descr-full').removeAttr('hidden').show();
                $('#descr-short').hide();
            });

            $(document).on('click', '#less', function(){
                $('#descr-short').removeAttr('hidden').show();
                $('#descr-full').hide();
            });

        })(jQuery)

    </script>

@endsection
