@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="podcasts">
            <div class="container">
                <div class="podcasts__header">
                    <h2 class="h2 text-center">Все подкасты</h2>
                </div>

                <div class="search">
                    <label for="search-name" class="search__icon">
                        <svg class="icon"><use href="/img/sprite.svg#search"></use></svg>
                    </label>
                    <input type="search" class="form-control" placeholder="Поиск подкаста" id="search-name" name="search-name" />
                    <button type="button" class="btn btn_filter" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <svg class="icon"><use href="/img/sprite.svg#sliders"></use></svg>
                    </button>
                </div>

                <div class="items-list__grid podcasts-container">
                    @foreach($podcasts as $p)
                        @include('partials.podcast-card')
                    @endforeach
                </div>
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
                        categories: categories.join(',')
                    },
                    success: function(response) {
                        $('.podcasts-container').html(response);
                    }
                })
            }
        })(jQuery);
    </script>

@endsection
