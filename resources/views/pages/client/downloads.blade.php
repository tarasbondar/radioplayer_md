@extends('layouts.client')

@section('content')

    <main class="main">
        <section class="podcast">
            <div class="container">
                <div class="podcast__wrap">
                    <div class="podcasts__header d-flex flex-row justify-content-between mb-24">
                        <h2 class="h2 mb-0">Скачанные файлы</h2>
                        @if(!empty($episodes))<a id="clear-downloads" class="btn btn-success">{{ __('app.clear') }}</a> @endif
                    </div>
                    <ul class="podcast__list">
                        @if(!empty($episodes))
                            @foreach($episodes as $episode)
                                @include('partials.episode-card')
                            @endforeach
                        @else
                            {{ __('app.listDownloadsIsEmpty') }}
                        @endif
                    </ul>
                </div>
            </div>

        </section>
    </main>

@endsection('content')
