@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="podcasts">
            <div class="container">
                <div class="podcasts__header">
                    <h2 class="h2 text-center">Подписки</h2>
                </div>

                <div class="items-list__grid">
                    @if (count($podcasts))
                        @foreach($podcasts as $p)
                            @include('partials.podcast-card')
                        @endforeach
                    @else
                        No subs
                    @endif
                </div>
            </div>
        </section>
    </main>

@endsection
