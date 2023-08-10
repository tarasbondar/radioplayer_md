@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} MetaTag</h2>

        <div class="row justify-content-center">
            <div class="col-md-12">

                <form method="POST" action="/admin/meta-tags/save" enctype = 'multipart/form-data'>
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
                                    <div class="col-md-6"> <input id="meta_title_{{ $language->code }}" type="text" class="form-control" name="meta_title_{{ $language->code }}" value="{{ (@$model) ? @$model->getTranslation('meta_title', $language->code) : '' }}"> </div>
                                </div>
                                <div class="mb-3 form-group row">
                                    <label for="meta_keywords_{{ $language->code }}" class="col-md-4 col-form-label text-md-right">Meta keywords ({{ $language->code }})</label>
                                    <div class="col-md-6"> <input id="meta_keywords_{{ $language->code }}" type="text" class="form-control" name="meta_keywords_{{ $language->code }}" value="{{ (@$model) ? @$model->getTranslation('meta_keywords', $language->code) : '' }}"> </div>
                                </div>

                                <div class="mb-3 form-group row">
                                    <label for="meta_description_{{ $language->code }}" class="col-md-4 col-form-label text-md-right">Meta description ({{ $language->code }})</label>
                                    <div class="col-md-6"> <input id="meta_description_{{ $language->code }}" type="text" class="form-control" name="meta_description_{{ $language->code }}" value="{{ (@$model) ? @$model->getTranslation('meta_description', $language->code) : '' }}"> </div>
                                </div>

                            </div>
                        @endforeach
                    </div>


                    <div class="form-group row" hidden>
                        <input id="id" type="text" class="form-control" name="id" value="{{ @$model['id'] }}" readonly>
                    </div>

                    <div class="mb-3 form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Route</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="route" value="{{ @$model['route'] }}" required>
                            <small id="emailHelp" class="form-text text-muted">Example: /all-podcasts, /history, /settings</small>
                        </div>

                    </div>

                    <div class="mb-3 form-group row">
                        <label for="is_default" class="col-md-4 col-form-label text-md-right">Is default</label>
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input type="hidden" name="is_default" value="0">
                                <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1" {{ (@$model['is_default']) ? 'checked' : ''}}>
                            </div>

                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary"> Save </button>
                            <a href="{{ request()->headers->get('referer') }}" class="btn"> Discard </a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection


