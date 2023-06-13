@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="podcasts">
            <div class="container">
                <div class="podcasts__header">
                    <h2 class="h2 text-center">{{ $username }}</h2>
                </div>
                <input id="author-id" type="text" value="{{ $author }}" hidden/>

                <div class="search">
                    <label for="search-name" class="search__icon">
                        <svg class="icon"><use href="/img/sprite.svg#search"></use></svg>
                    </label>
                    <input type="search" class="form-control" placeholder="{{ __('client.searchPodcast') }}" id="search-name" name="search-name" />
                    <button type="button" class="btn btn_filter" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <svg class="icon"><use href="/img/sprite.svg#sliders"></use></svg>
                    </button>
                </div>

                @if(count($podcasts))
                    <div class="items-list__grid podcasts-container">
                        @foreach($podcasts as $p)
                            @include('partials.podcast-card')
                        @endforeach
                    </div>
                @else
                    Author has no available podcasts
                @endif
            </div>
        </section>
    </main>

    @include('partials/podcasts-filter')
@endsection
