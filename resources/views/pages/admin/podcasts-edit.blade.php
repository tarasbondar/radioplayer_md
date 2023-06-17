@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} podcast</h2>

        <div class="row justify-content-center">
            <div class="col-md-12">

                <form method="POST" action="/admin/podcasts/save" enctype = 'multipart/form-data'>
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
                                    <div class="col-md-6"> <input id="meta_title_{{ $language->code }}" type="text" class="form-control" name="meta_title_{{ $language->code }}" value="{{ (@$podcast) ? @$podcast->getTranslation('meta_title', $language->code) : '' }}"> </div>
                                </div>
                                <div class="mb-3 form-group row">
                                    <label for="meta_keywords_{{ $language->code }}" class="col-md-4 col-form-label text-md-right">Meta keywords ({{ $language->code }})</label>
                                    <div class="col-md-6"> <input id="meta_keywords_{{ $language->code }}" type="text" class="form-control" name="meta_keywords_{{ $language->code }}" value="{{ (@$podcast) ? @$podcast->getTranslation('meta_keywords', $language->code) : '' }}"> </div>
                                </div>

                                <div class="mb-3 form-group row">
                                    <label for="meta_description_{{ $language->code }}" class="col-md-4 col-form-label text-md-right">Meta description ({{ $language->code }})</label>
                                    <div class="col-md-6"> <input id="meta_description_{{ $language->code }}" type="text" class="form-control" name="meta_description_{{ $language->code }}" value="{{ (@$podcast) ? @$podcast->getTranslation('meta_description', $language->code) : '' }}"> </div>
                                </div>

                            </div>
                        @endforeach
                    </div>


                    <div class="form-group row" hidden>
                        <input id="id" type="text" class="form-control" name="id" value="{{ @$podcast['id'] }}" readonly>
                    </div>
                    <div class="form-group row" hidden>
                        <input id="owner-id" type="text" class="form-control" name="owner-id" value="{{ isset($podcast['id']) ? $podcast['id'] : ''}}" readonly>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                        <div class="col-md-6"> <input id="name" type="text" class="form-control" name="name" value="{{ @$podcast['name'] }}" required> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                        <div class="col-md-6"> <textarea id="description" type="text" class="form-control ckeditor-custom" name="description" required> {{ @$podcast['description'] }} </textarea> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="tags" class="col-md-4 col-form-label text-md-right">Tags</label>
                        <div class="col-md-6"> <input id="tags" type="text" class="form-control" name="tags" value="{{ @$podcast['tags'] }}"> </div>
                    </div>

                    <div class="mb-3 form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Current Image</label>
                        <div class="col-md-6">
                                @if (!empty($podcast['image']))
                                    <img id="image" alt="" src="/uploads/podcasts_images/{{ $podcast['image'] }}" width="325" height="325"/>
                                @else
                                    -
                                @endif
                        </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="image" class="col-md-4 col-form-label text-md-right">Upload Image</label>
                        <div class="col-md-6"><input id="image" type="file" name="image" accept=".png, .jpg, .jpeg"></div>
                    </div>

                    <div class="mb-3 form-group row">
                        <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status-active" value="0" {{ empty(@$podcast['status']) ? 'checked' : ''}}>
                                <label class="form-check-label" for="status-active">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status-inactive" value="1" {{ @$podcast['status'] == 1 ? 'checked' : ''}}>
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


