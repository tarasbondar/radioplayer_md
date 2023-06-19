@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} episode</h2>

        <div class="row justify-content-center">
            <div class="col-md-12">

                <form method="POST" action="/admin/podcasts-episodes/save" enctype = 'multipart/form-data'>
                    @csrf

                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        @foreach(\App\Helpers\LanguageHelper::getLanguages() as $key => $language)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ ($key == 0) ? 'active' : '' }}" id="lang-{{ $language->code }}-tab" data-bs-toggle="tab" data-bs-target="#lang-{{ $language->code }}" type="button" role="tab" aria-controls="home" aria-selected="true">{{ $language->name }}</button>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        @foreach(\App\Helpers\LanguageHelper::getLanguages() as $key => $language)
                            <div class="tab-pane fade {{ ($key == 0) ? 'show active' : '' }}" id="lang-{{ $language->code }}" role="tabpanel" aria-labelledby="lang-{{ $language->code }}-tab">

                                <div class="mb-3 form-group row">
                                    <label for="meta_title_{{ $language->code }}" class="col-md-4 col-form-label text-md-right">Meta title ({{ $language->code }})</label>
                                    <div class="col-md-6"> <input id="meta_title_{{ $language->code }}" type="text" class="form-control" name="meta_title_{{ $language->code }}" value="{{ (@$episode) ? @$episode->getTranslation('meta_title', $language->code) : '' }}"> </div>
                                </div>
                                <div class="mb-3 form-group row">
                                    <label for="meta_keywords_{{ $language->code }}" class="col-md-4 col-form-label text-md-right">Meta keywords ({{ $language->code }})</label>
                                    <div class="col-md-6"> <input id="meta_keywords_{{ $language->code }}" type="text" class="form-control" name="meta_keywords_{{ $language->code }}" value="{{ (@$episode) ? @$episode->getTranslation('meta_keywords', $language->code) : '' }}"> </div>
                                </div>

                                <div class="mb-3 form-group row">
                                    <label for="meta_description_{{ $language->code }}" class="col-md-4 col-form-label text-md-right">Meta description ({{ $language->code }})</label>
                                    <div class="col-md-6"> <input id="meta_description_{{ $language->code }}" type="text" class="form-control" name="meta_description_{{ $language->code }}" value="{{ (@$episode) ? @$episode->getTranslation('meta_description', $language->code) : '' }}"> </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="form-group row" hidden>
                        <input id="id" type="text" class="form-control" name="id" value="{{ @$episode['id'] }}" readonly>
                    </div>

                    <div class="mb-3 form-group row">
                        <label for="podcast" class="col-md-4 col-form-label text-md-right">Подкаст</label>
                        <div class="col-md-6">
                            @if (isset($podcast))
                                <select class="form-select" name='podcast' id="podcast" aria-label="Episodes podcast">
                                    <option value="{{$podcast['id']}}" selected> {{ $podcast['name'] }} </option>
                                </select>
                            @elseif (isset($podcasts))
                                <select class="form-select" name='podcast' id="podcast" aria-label="{{ __('client.selectPodcast') }}">
                                    @foreach($podcasts as $p)
                                        <option value="{{$p['id']}}" {{ (isset($episode) && $p['id'] == $episode['podcast_id']) ? 'selected' : '' }}> {{ $p['name'] }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Название</label>
                        <div class="col-md-6"> <input id="name" type="text" class="form-control" name="name" value="{{ @$episode['name'] }}" required> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="description" class="col-md-4 col-form-label text-md-right">Описание</label>
                        <div class="col-md-6"> <textarea id="description" type="text" class="form-control ckeditor-custom" name="description" required> {{ @$episode['description'] }} </textarea> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="tags" class="col-md-4 col-form-label text-md-right">Тэги</label>
                        <div class="col-md-6"> <input id="tags" type="text" class="form-control " name="tags" value="{{ @$episode['tags'] }}" required>  </div>
                    </div>

                    <div class="mb-3 form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Текущий файл</label>
                        <div class="col-md-6">
                            @if(!empty($episode['source']))
                                <audio id="audio" controls class="form-control"><source src="/uploads/podcasts_episodes/{{ $episode['source'] }}">
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="source" class="col-md-4 col-form-label text-md-right">Загрузить</label>
                        <div class="col-md-6"><input id="source" type="file" name="source" accept=".mp3, .wav"></div>
                    </div>

                    <div class="mb-3 form-group row">
                        <label for="status" class="col-md-4 col-form-label text-md-right">Статус</label>
                        <div class="col-md-6">
                            <select class="form-select" name='status' id="status" aria-label="Select episode status">
                                <option value="1"> {{ 'Черновик' }} </option>
                                <option value="2"> {{ 'Опубликован' }} </option>
                                <option value="0"> {{ 'Заблокирован' }} </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary"> Сохранить </button>
                            <a href="{{ request()->headers->get('referer') }}" class="btn"> Отклонить </a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
