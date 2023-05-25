@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="author">

            <div class="container">

                <div class="author__header">
                    <h2 class="h2 text-center">{{ __('client.becomeAuthor') }}</h2>
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

    @if ($status == 'new')
        <div class="modal modal-filter fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="h3 modal-title text-center" id="exampleModalLabel">{{ __('client.categories') }}</h3>
                        <button type="button" class="btn-close btn btn_ico close-categories" data-bs-dismiss="modal" aria-label="Close">
                            <svg class="icon">
                                <use href="/img/sprite.svg#x"></use>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="list categories-list">
                                @foreach($categories as $c)
                                    <div class="input input__inner">
                                        <input class="input__checkbox" type="checkbox" id="category-{{$c['id']}}" name="{{ $c['key'] }}" value="{{$c['id']}}">
                                        <label class="input__label light" for="category-{{$c['id']}}"> {{ $c['key'] }} </label>
                                        <svg class="icon">
                                            <use href="/img/sprite.svg#check"></use>
                                        </svg>
                                        <div class="messages"></div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-actions">
                                <button class="btn btn_default btn_primary close-categories" data-bs-dismiss="modal">{{ __('client.save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
