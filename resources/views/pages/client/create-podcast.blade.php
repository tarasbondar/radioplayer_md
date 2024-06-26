@extends('layouts/client')

@section('content')

    <main class="main">
        <section class="author">
            <div class="container">
                <div class="author__wrapper">
                    <form action="/save-podcast" method="POST" id="podcast-form" {{--data-validate="apply-form"--}} enctype="multipart/form-data">

                        @csrf

                        <div class="form-group row" hidden>
                            <input id="id" type="text" class="form-control" name="id" value="{{ @$podcast['id'] }}" readonly>
                        </div>

                        <div class="input form-floating">
                            <input type="text" class="form-control" placeholder="{{ __('client.podcastTitle') }}" id="name" name="name" value="{{ @$podcast['name'] }}" required>
                            <label for="name">{{ __('client.podcastTitle') }}</label>
                            <div class="messages"></div>
                        </div>

                        <div class="input form-floating">
                            <div class="form-control ckeditor-custom"> {!! @$podcast['description'] !!} </div>
                            <textarea class="form-control d-none" id="description" name="description"> </textarea>
                            <label for="description">{{ __('client.podcastDescription') }}</label>
                            <div class="messages"></div>
                        </div>

                        {{--<div class="input form-floating">
                            <div class="input__editor" data-editor>{{ @$podcast['description'] }}</div>
                        </div>--}}

                        <div class="form-group row" hidden>
                            <input id="categories-ids" name='categories-ids' type="text" class="form-control" value="{{ implode(',', array_keys($p2c)) }}" readonly>
                        </div>

                        <div class="input ">
                            <div class="form-floating">
                                <input class="form-control" type="text" placeholder="{{ __('client.category') }}" id="categories-keys" name="categories" value="{{ implode(',', $p2c) }}" readonly> {{--categories keys here--}}
                                <label for="categories">{{ __('client.category') }}</label>

                                <button class="btn-modal-toggle-wrapper" type="button" aria-label="{{ __('client.categories') }}" data-bs-toggle="modal" data-bs-target="#categoriesModal">
                                    <span class="btn btn-modal-toggle">
                                        <svg class="icon">
                                            <use href="/img/sprite.svg#chevron-right"></use>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            <div class="messages"></div>
                        </div>

                        <div class="input form-floating">
                            <input type="text" class="form-control" placeholder="{{ __('client.tagsComma') }}" id="tags" name="tags" value="{{ @$podcast['tags'] }}">
                            <label for="tags">{{ __('client.tagsComma') }}</label>
                            <div class="messages"></div>
                        </div>

                        <div class="input file">
                            <label for="file-image" class="control-panel">
                                <input id="file-image" class="form__file" type="file" data-file_input="file-image" size="1048576" name="image" accept="image/png, image/jpeg, image/*">
                                <span class="control-panel-wrap">
                                        <span class="control-panel-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                <path d="M19.5 3H5.5C4.39543 3 3.5 3.89543 3.5 5V19C3.5 20.1046 4.39543 21 5.5 21H19.5C20.6046 21 21.5 20.1046 21.5 19V5C21.5 3.89543 20.6046 3 19.5 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M9 10C9.82843 10 10.5 9.32843 10.5 8.5C10.5 7.67157 9.82843 7 9 7C8.17157 7 7.5 7.67157 7.5 8.5C7.5 9.32843 8.17157 10 9 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M21.5 15L16.5 10L5.5 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                        <span class="control-panel-info">
                                            <span class="h4 authorization-list-card__title">{{ __('client.podcastImage') }}</span>
                                            <span class="authorization-list-card__desc">{{ __('client.podcastImageRequirements') }}</span>
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
                            <input class="toggle-checkbox" type="checkbox" id="status" name="status" value="0" {{ @$podcast['status'] == 0 ? 'checked' : ''}}>
                            <span class="toggle-switch"></span>
                            <span class="toggle-label">{{ __('client.published') }}</span>
                        </label>

                        @if ($action == 'edit')
                            <div class="input__actions mt-0">
                                <button class="btn btn_secondary btn_large" id='delete-podcast' value="{{ @$podcast['id'] }}" type="button">{{ __('client.delete') }}</button>
                                <button class="btn btn_primary btn_large" type="submit">{{ __('client.save') }}</button>
                            </div>
                        @endif
                        @if ($action == 'add')
                            <button class="btn btn_default btn_primary" type="submit">{{ __('client.save') }}</button>
                        @endif

                    </form>
                </div>
            </div>
        </section>
    </main>

    <div class="modal modal-filter fade" id="categoriesModal" tabindex="-1" aria-labelledby="categoriesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="h3 modal-title text-center" id="categoriesModalLabel">{{ __('client.categories') }}</h3>
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
                                <input class="input__checkbox categories-check" type="checkbox" id="category-{{ $c['id'] }}" value="{{$c->id . '-' . $c->getTranslation('title')}}" {{in_array($c->id, array_keys($p2c)) ? 'checked' : ''}}>
                                <label class="input__label light" for="category-{{ $c['id'] }}">
                                    {{ $c->getTranslation('title') }}
                                </label>
                                <svg class="icon"><use href="/img/sprite.svg#check"></use></svg>
                                <div class="messages"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-actions">
                        <button class="btn btn_default btn_primary" data-bs-dismiss="modal">{{ __('client.save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
