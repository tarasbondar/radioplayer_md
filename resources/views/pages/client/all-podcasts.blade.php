@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="podcasts">
            <div class="container">
                <div class="search">
                    <label for="search-field" class="search__icon">
                        <svg class="icon">
                            <use href="img/sprite.svg#search"></use>
                        </svg>
                    </label>
                    <input type="search" class="form-control" placeholder="{{ __('client.searchPodcast') }}" id="search-field" name="search-field" />
                    <button type="button" class="btn btn_filter" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <svg class="icon">
                            <use href="img/sprite.svg#sliders"></use>
                        </svg>
                    </button>
                </div>

                <div class="podcasts__header d-flex flex-row justify-content-between mb-24">
                    <h2 class="h2 mb-0">{{ __('client.allPodcasts') }}</h2>
                    {{--@if(count($podcasts) > 5)--}} <a href="/podcasts" class="link">Больше</a> {{--@endif--}}
                </div>
                <div class="items-list__grid mb-24 podcasts-container">
                    @foreach($podcasts as $p)
                        @include('partials.podcast-card')
                    @endforeach
                </div>

                <div class="podcast__wrap">
                    <ul class="podcast__list episodes-container">
                        @foreach($episodes as $episode)
                            @include('partials.episode-card')
                        @endforeach
                    </ul>
                </div>
                <input id="allsearch-page" value="1" hidden>

            </div>
        </section>
    </main>

    @include('partials/podcasts-filter')

@endsection
