@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} Value</h2>

        <div class="row justify-content-center">
            <div class="col-md-10">

                <form method="POST" action="/admin/custom-values/save">
                    @csrf

                    <div class="mb-3 form-group row">
                        <label for="key" class="col-md-4 col-form-label text-md-right">Ключ</label>
                        <div class="col-md-6"> <input id="key" type="text" class="form-control" name="key" value="{{ @$value['key'] }}" required> </div>
                    </div>

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
                                    <label for="title_{{ $language->code }}" class="col-md-4 col-form-label text-md-right">Описание ({{ $language->code }})</label>
                                    <div class="col-md-6">
                                        <textarea id="description_{{ $language->code }}" type="text" class="form-control ckeditor-custom" name="description_{{ $language->code }}" required> {{ (@$value) ? @$value->getTranslation('description', $language->code) : '' }} </textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group row" hidden>
                        <input id="id" type="text" class="form-control" name="id" value="{{ @$value['id'] }}" readonly>
                    </div>

{{--                    <div class="mb-3 form-group row">--}}
{{--                        <label for="value" class="col-md-4 col-form-label text-md-right">Value</label>--}}
{{--                        <div class="col-md-6"> <textarea id="value" type="text" class="form-control ckeditor-custom" name="value" required> {{ @$value['value'] }} </textarea> </div>--}}
{{--                    </div>--}}

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary"> Сохранить </button>
                            <a href="{{ request()->headers->get('referer') }}" class="btn"> Отменить </a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection



