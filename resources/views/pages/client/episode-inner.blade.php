@extends('layouts/client')

<?php
use App\Helpers\SiteHelper;
use App\Models\PodcastEpisode;
?>

@section('content')

    <main class="main">
        <section class="podcast">
            <div class="container">
                <div class="podcast__wrap">
                    <div class="podcast__inner">
                        <a class="btn btn_link small podcast__episode-back" data-ignore href="javascript:void(0);">
                            <svg class="icon">
                                <use href="/img/sprite.svg#chevron-left"></use>
                            </svg>
                            {{ __('app.back') }}
                        </a>

                        <div class="podcast__descr-block">
                            <div class="podcast-img">
                                <img src="{{ !empty($podcast['image']) ? '/uploads/podcasts_images/' . $podcast['image'] : "/img/podcast-placeholder.png"}}"
                                     srcset="{{ !empty($podcast['image']) ? '/uploads/podcasts_images/' . $podcast['image'] : "/img/podcast-placeholder.png"}} 1x,
                                     {{ !empty($podcast['image']) ? '/uploads/podcasts_images/' . $podcast['image'] : "/img/podcast-placeholder.png"}} 2x" alt="img">
                            </div>
                            <div class="text">
                                <span class="podcast__descr"><a href="/author/{{ $podcast['owner_id'] }}">{{ $podcast['username'] }}</a></span>
                                <strong class="podcast__title">{{ $podcast['name'] }}</strong>
                            </div>
                        </div>
                        <span class="podcast__data">{{ $episode['created_diff'] }}</span>
                        <strong class="podcast__title">{{ $episode['name'] }}</strong>
                        <div class="podcast__holder">
                            <div class="podcast__timer play-episode {{ ($episode['is_in_history']) ? 'active' : '' }}" data-play-episode="{{ $episode['id'] }}" data-episode="{{ $episode['id'] }}">
                                <div data-card-icon-play>
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_801_29988)">
                                            <path d="M2.5 1.5L9.5 6L2.5 10.5V1.5Z" fill="currentColor" stroke="currentColor" stroke-width="0.666667" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_801_29988">
                                                <rect width="12" height="12" fill="white"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <div data-card-icon-pause hidden>
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 2H3V10H5V2Z" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M9 2H7V10H9V2Z" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                                <span data-is_listened="0" data-duration-left="{{ $episode['id'] }}" class="{{ $episode['is_listened'] ? 'hidden' : '' }}">{{ @$episode['duration_left_label'] }}</span>
                                <span data-is_listened="1" class="{{ !$episode['is_listened'] ? 'hidden' : '' }}">{{ __('client.listened') }}</span>
                            </div>
                        </div>
                        <p class="podcast__text">{!! $episode['description'] !!}</p>
                    </div>

                </div>
            </div>

            @if(auth()->id() == $podcast['owner_id'])
                <a href="/edit-episode/{{$episode['id']}}" class="btn btn_ico btn_ico-accent page-btn _playing" aria-label="{{ __('app.edit') }}">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 1.99981C16.2626 1.73717 16.5744 1.52883 16.9176 1.38669C17.2608 1.24455 17.6286 1.17139 18 1.17139C18.3714 1.17139 18.7392 1.24455 19.0824 1.38669C19.4256 1.52883 19.7374 1.73717 20 1.99981C20.2626 2.26246 20.471 2.57426 20.6131 2.91742C20.7553 3.26058 20.8284 3.62838 20.8284 3.99981C20.8284 4.37125 20.7553 4.73905 20.6131 5.08221C20.471 5.42537 20.2626 5.73717 20 5.99981L6.5 19.4998L1 20.9998L2.5 15.4998L16 1.99981Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            @endif

        </section>
    </main>

@endsection
