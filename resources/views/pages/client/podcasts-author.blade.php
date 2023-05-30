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

    <script>
        (function() {
            $(document).on('change', '#search-name', function() {
                searchPodcast();
            });

            $(document).on('change', '.input__checkbox', function () {
                searchPodcast();
            });

            function searchPodcast() {
                let categories = [];
                $('.input__checkbox:checked').each(function(){
                    categories.push($(this).val());
                });

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/update-podcasts',
                    data: {
                        name: $('#search-name').val(),
                        categories: categories.join(','),
                        author: $('#author-id').val()
                    },
                    success: function(response) {
                        $('.podcasts-container').html(response);
                    }
                })
            }
        })(jQuery);
    </script>

@endsection
