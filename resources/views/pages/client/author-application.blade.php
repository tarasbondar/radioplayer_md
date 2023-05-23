@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="author">

            <div class="container">

                <div class="author__header">
                    <h2 class="h2 text-center">Стать автором</h2>
                </div>

                @if ($status == 'new')
                    @include('partials.application-form')
                @elseif ($status == 'pending')
                    @include('partials.application-in-progress')
                @elseif ($status == 'declined')
                    @include('partials.application-declined', ['feedback' => $feedback])
                @endif
                {{--declined retry?--}}

            </div>
        </section>
    </main>



@endsection
