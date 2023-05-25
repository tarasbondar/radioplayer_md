@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="podcast">
            <div class="container">
                <div class="podcast__wrap">
                    <div class="podcasts__header">
                        <h2 class="h2 text-center">Прослушать позже</h2>
                    </div>
                    <ul class="podcast__list">
                        @foreach($episodes as $episode)
                            @include('partials/episode-card')
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    </main>

@endsection
