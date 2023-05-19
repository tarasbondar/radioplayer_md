@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="author">
            <div class="container">
                <div class="author__wrapper">
                    <form action="/save-podcast" method="POST" id="podcast-form" {{--data-validate="apply-form"--}}>

                        @csrf

                        <div class="form-group row" hidden>
                            <input id="id" type="text" class="form-control" name="id" value="{{ @$podcast['id'] }}" readonly>
                        </div>

                        {{--<div class="form-group row" hidden>
                            <input id="owner-id" type="text" class="form-control" name="owner-id" value="{{ isset($podcast['id']) ? $podcast['id'] : ''}}" readonly>
                        </div>--}}

                        <div class="input form-floating">
                            <input type="text" class="form-control" placeholder="Название подкаста" id="name" name="name" value="{{ @$podcast['name'] }}" required>
                            <label for="name">Название подкаста</label>
                            <div class="messages"></div>
                        </div>

                        <div class="input form-floating">
                            <textarea class="form-control" placeholder="Описание подкаста" id="description" name="description" required> {{ @$podcast['description'] }} </textarea>
                            <label for="description">Описание подкаста</label>
                            <div class="messages"></div>
                        </div>

                        <div class="form-group row" hidden>
                            <input id="categories-ids" name='categories-ids' type="text" class="form-control" value="{{ implode(',', array_keys($p2c)) }}" readonly>
                        </div>

                        <div class="input form-floating">
                            <input class="form-control" placeholder="Категория" id="categories-keys" name="categories" value="{{ implode(',', $p2c) }}" readonly> {{--categories keys here--}}
                            <label for="categories">Категория</label>
                            <div class="messages"></div>
                            <button class="btn btn-modal-toggle" type="button" aria-expanded="false" data-bs-toggle="modal" data-bs-target="#categoriesModal">
                                <svg class="icon">
                                    <use href="/img/sprite.svg#chevron-right"></use>
                                </svg>
                            </button>
                        </div>

                        <div class="input form-floating">
                            <input type="text" class="form-control" placeholder="Теги через запятую" id="tags" name="tags" value="{{ @$podcast['tags'] }}">
                            <label for="tags">Теги через запятую</label>
                            <div class="messages"></div>
                        </div>

                        <div class="input file">
                            <label for="file-image" class="control-panel">
                                <input id="file-image" class="form__file" type="file" data-file_input="file-image" size="1048576" accept="image/png, image/jpeg, image/*">
                                <span class="control-panel-wrap">
                                        <span class="control-panel-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                <path d="M19.5 3H5.5C4.39543 3 3.5 3.89543 3.5 5V19C3.5 20.1046 4.39543 21 5.5 21H19.5C20.6046 21 21.5 20.1046 21.5 19V5C21.5 3.89543 20.6046 3 19.5 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M9 10C9.82843 10 10.5 9.32843 10.5 8.5C10.5 7.67157 9.82843 7 9 7C8.17157 7 7.5 7.67157 7.5 8.5C7.5 9.32843 8.17157 10 9 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M21.5 15L16.5 10L5.5 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                        <span class="control-panel-info">
                                            <span class="h4 authorization-list-card__title">Изображение подкаста</span>
                                            <span class="authorization-list-card__desc">1080x1080 px, 1 MB максимум</span>
                                            <img class="authorization-list-card__preview" src="" alt="preview">
                                            <i class="control-panel-delete icon-close">
                                                <svg class="icon">
                                                    <use href="/img/sprite.svg#x"></use>
                                                </svg>
                                            </i>
                                        </span>
                                    </span>
                            </label>
                        </div>


                        <label class="toggle mb-24">
                            <input class="toggle-checkbox" type="checkbox" id="status" name="status" value="0" {{ @$podcast['status'] == 0 ? 'checked' : ''}} {{--checked--}}>
                            <span class="toggle-switch"></span>
                            <span class="toggle-label">Опубликовано</span>
                        </label>

                        @if ($action == 'edit')
                            <div class="input__actions mt-0">
                                <button class="btn btn_secondary btn_large" type="button">Удалить</button>
                                <button class="btn btn_primary btn_large" type="submit">Сохранить</button>
                            </div>
                        @endif
                        @if ($action == 'add')
                            <button class="btn btn_default btn_primary" type="submit">Сохранить</button>
                        @endif

                    </form>
                </div>

                <div class="modal modal-filter fade" id="categoriesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="h3 modal-title text-center" id="exampleModalLabel">Категории</h3>
                                <button type="button" class="btn-close btn btn_ico" data-bs-dismiss="modal" aria-label="Close">
                                    <svg class="icon">
                                        <use href="/img/sprite.svg#x"></use>
                                    </svg>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="list">
                                    @foreach($categories as $c)
                                        <div class="input input__inner categories-checkbox">
                                            <input class="input__checkbox categories-check" type="checkbox" id="category-{{ $c['id'] }}" value="{{$c->id . '-' . $c->key}}" {{in_array($c->id, array_keys($p2c)) ? 'checked' : ''}}>
                                            <label class="input__label light" for="category-{{ $c['id'] }}">
                                                {{ $c['key'] }}
                                            </label>
                                            <svg class="icon"><use href="/img/sprite.svg#check"></use></svg>
                                            <div class="messages"></div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-actions">
                                    <button class="btn btn_default btn_primary" data-bs-dismiss="modal">Сохранить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <script>
        (function() {

            $(document).on('click', '.categories-checkbox > input', function () {
                let categories_ids = [];
                let categories_keys = [];
                    $('.categories-check:checkbox:checked').each(function () {
                        let val = $(this).val().split("-");
                        categories_ids.push(val[0]);
                        categories_keys.push(val[1]);
                });
                $('#categories-ids').val(categories_ids.join(','));
                $('#categories-keys').val(categories_keys.join(', '));
            });

        })(jQuery);

    </script>

@endsection
