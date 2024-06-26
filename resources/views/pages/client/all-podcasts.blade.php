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
                    <input type="search" class="form-control" placeholder="{{ __('client.searchPodcast') }}" id="search-field-all" name="search-field" />
                    <button type="button" class="btn btn_filter" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <svg class="icon">
                            <use href="img/sprite.svg#sliders"></use>
                        </svg>
                    </button>
                </div>

                <div class="podcasts__header d-flex flex-row justify-content-between mb-24">
                    <h2 class="h2 mb-0">{{ __('client.allPodcasts') }}</h2>
                    {{--@if(count($podcasts) > 5)--}} <a href="/podcasts" class="link"> {{'Больше'}}</a> {{--@endif--}}
                </div>
                <div class="items-list__grid mb-24 podcasts-container all-podcasts overflow-auto">
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
                <input id="allsearch-last" value="{{ $page_count }}" hidden>

            </div>
        </section>
    </main>

    <div class="modal modal-filter fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="h3 modal-title text-center" id="exampleModalLabel">{{ __('client.categories') }}</h3>
                    <button type="button" class="btn-close btn btn_ico" data-bs-dismiss="modal" aria-label="{{ __('app.close') }}">
                        <svg class="icon">
                            <use href="/img/sprite.svg#x"></use>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="list">
                        @foreach($categories as $c)
                            <div class="input input__inner">
                                <input class="input__checkbox input__checkbox-all" type="checkbox" id="category-{{$c['id']}}" value="{{$c['id']}}">
                                <label class="input__label light" for="category-{{$c['id']}}">
                                    {{ $c->getTranslation('title') }}
                                </label>
                                <svg class="icon"><use href="/img/sprite.svg#check"></use></svg>
                                <div class="messages"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-actions">
                        <button class="btn btn_default btn_primary" data-bs-dismiss="modal">{{ __('app.save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
