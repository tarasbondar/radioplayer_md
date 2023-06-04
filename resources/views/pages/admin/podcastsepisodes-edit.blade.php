@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} episode</h2>

        <div class="row justify-content-center">
            <div class="col-md-12">

                <form method="POST" action="/admin/podcasts-episodes/save" enctype = 'multipart/form-data'>
                    @csrf
                    <div class="form-group row" hidden>
                        <input id="id" type="text" class="form-control" name="id" value="{{ @$episode['id'] }}" readonly>
                    </div>

                    <div class="mb-3 form-group row">
                        <label for="podcast" class="col-md-4 col-form-label text-md-right">Podcast</label>
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
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                        <div class="col-md-6"> <input id="name" type="text" class="form-control" name="name" value="{{ @$episode['name'] }}" required> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                        <div class="col-md-6"> <textarea id="description" type="text" class="form-control ckeditor-custom" name="description" required> {{ @$episode['description'] }} </textarea> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="tags" class="col-md-4 col-form-label text-md-right">Tags</label>
                        <div class="col-md-6"> <input id="tags" type="text" class="form-control " name="tags" value="{{ @$episode['tags'] }}" required>  </div>
                    </div>

                    <div class="mb-3 form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Current Source</label>
                        <div class="col-md-6">
                            @if(!empty($episode['source']))
                                <audio id="audio" controls class="form-control"><source src="/uploads/podcasts_episodes/{{ $episode['source'] }}">
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="source" class="col-md-4 col-form-label text-md-right">Upload</label>
                        <div class="col-md-6"><input id="source" type="file" name="source" accept=".mp3, .wav"></div>
                    </div>

                    <div class="mb-3 form-group row">
                        <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>
                        <div class="col-md-6">
                            <select class="form-select" name='status' id="status" aria-label="Select episode status">
                                <option value="1"> {{ 'Draft' }} </option>
                                <option value="2"> {{ 'Published' }} </option>
                                <option value="0"> {{ 'Blocked' }} </option>
                            </select>
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
