@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} episode</h2>

        <div class="row justify-content-center">
            <div class="col-md-10">

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
                                <select class="form-select" name='podcast' id="podcast" aria-label="Select podcast">
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
                    <div class="mb-3 form-group row"> {{-- Text editor here --}}
                        <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                        <div class="col-md-6"> <textarea id="description" type="text" class="form-control " name="description" rows="6" required> {{ @$episode['description'] }} </textarea> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="tags" class="col-md-4 col-form-label text-md-right">Tags</label>
                        <div class="col-md-6"> <input id="tags" type="text" class="form-control " name="tags" value="{{ @$episode['tags'] }}" required>  </div>
                    </div>
                    {{--<div class="mb-3 form-group row">
                        <label for="source" class="col-md-4 col-form-label text-md-right">Source</label>
                        <div class="col-md-6"> <input id="source" type="text" class="form-control " name="source" value="{{ @$episode['source'] }}" required>  </div>
                    </div>--}}
                    <div class="mb-3 form-group row">
                        <label for="tags" class="col-md-4 col-form-label text-md-right">Audio</label>
                        <div class="col-md-6">
                            <div id="audio-upload">
                                <button id="browse" class="btn btn-primary">Browse</button>
                            </div>
                            <div  style="display: none; height: 25px" class="progress mt-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">0</div>
                            </div>
                        </div>
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

    <script type="text/javascript">
        (function() {
            let resumable = new Resumable({
                target: '{{ '/admin/podcasts-episodes/upload-audio' }}',
                query: {_token: '{{ csrf_token() }}'},
                chunkSize: 10 * 1024 * 1024,
                testChunks: false,
                throttleProgressCallbacks: 1,
            });

            let browse = $('#browse');
            resumable.assignBrowse(browse[0]);

            let progress = $('.progress');

            resumable.on('fileAdded', function (file) {
                progress.find('.progress-bar').css('width', '0%');
                progress.find('.progress-bar').html('0%');
                progress.find('.progress-bar').removeClass('bg-success');
                progress.show();
                resumable.upload();
            });

            resumable.on('fileProgress', function (file) {
                let update_progress = Math.floor(file.progress() * 100);
                progress.find('.progress-bar').css('width', `${update_progress}%`);
                progress.find('.progress-bar').html(`${update_progress}%`)
            });

            resumable.on('fileSuccess', function (file) {
                window.alert(file.fileName + ' was successfully uploaded');
            });

            resumable.on('fileError', function (file, response) {
                window.alert(response);
            });
        })(jQuery)
    </script>

@endsection


