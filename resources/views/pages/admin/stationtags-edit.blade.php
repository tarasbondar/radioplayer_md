@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} Radio Station Tag</h2>

        <div class="row justify-content-center">
            <div class="col-md-10">

                <form method="POST" action="/admin/station-tags/save">
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
                                    <label for="title_{{ $language->code }}" class="col-md-4 col-form-label text-md-right">Title ({{ $language->code }})</label>
                                    <div class="col-md-6"> <input id="title_{{ $language->code }}" type="text" class="form-control" name="title_{{ $language->code }}" value="{{ (@$tag) ? @$tag->getTranslation('title', $language->code) : '' }}"> </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group row" hidden>
                        <input id="id" type="text" class="form-control" name="id" value="{{ @$tag['id'] }}" readonly>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="key" class="col-md-4 col-form-label text-md-right">Admin notes</label>
                        <div class="col-md-6"> <input id="key" type="text" class="form-control" name="key" value="{{ @$tag['key'] }}" required> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>
                        <div class="col-md-6 py-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status-active" value="0" {{ empty(@$tag['status']) ? 'checked' : ''}}>
                                <label class="form-check-label" for="status-active">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status-inactive" value="1" {{ @$tag['status'] == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="status-inactive">Inactive</label>
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



