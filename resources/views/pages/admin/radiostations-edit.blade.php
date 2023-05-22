@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} Station</h2>

        <div class="row justify-content-center">
            <div class="col-md-10">

                <form method="POST" action="/admin/stations/save" enctype='multipart/form-data'>
                    @csrf
                    <div class="form-group row" hidden>
                        <input id="id" type="text" class="form-control" name="id" value="{{ @$station['id'] }}" readonly>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                        <div class="col-md-6"> <input id="name" type="text" class="form-control" name="name" value="{{ @$station['name'] }}" required> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                        <div class="col-md-6"> <textarea id="description" type="text" class="form-control " name="description" rows="6" required> {{ @$station['description'] }} </textarea> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="source" class="col-md-4 col-form-label text-md-right">Source</label>
                        <div class="col-md-6"> <input id="source" type="text" class="form-control" name="source" value="{{ @$station['source'] }}" required> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="source-hd" class="col-md-4 col-form-label text-md-right">Source HD</label>
                        <div class="col-md-6"> <input id="source-hd" type="text" class="form-control" name="source-hd" value="{{ @$station['source_hd'] }}"> </div>
                    </div>

                    <div class="mb-3 form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Current Image</label>
                        <div class="col-md-6"> <div class="form-control">{{ $station['image_logo'] ?? 'none' }} </div></div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="image" class="col-md-4 col-form-label text-md-right">Upload Image</label>
                        <div class="col-md-6"><input id="image" type="file" name="image"></div>
                    </div>

                    <div class="mb-3 form-group row">
                        <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>
                        <div class="col-md-6 py-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status-active" value="0" {{ empty(@$station['status']) ? 'checked' : ''}}>
                                <label class="form-check-label" for="status-active">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status-inactive" value="1" {{ @$station['status'] == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="status-inactive">Inactive</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" hidden>
                        <input id="categories-connections" type="text" class="form-control" name="categories" value="{{ implode(',', $s2c) }}" readonly>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="categories-checkbox" class="col-md-4 col-form-label text-md-right">Categories</label>
                        @foreach($categories as $c)
                            <div class="form-check form-check-inline categories-checkbox">
                                <input class="form-check-input categories-check" type="checkbox" id="category-{{$c->id}}" value="{{$c->id}}" {{in_array($c->id, $s2c) ? 'checked' : ''}}>
                                <label class="form-check-label" for="category-{{$c->id}}">{{$c->key}}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group row" hidden>
                        <input id="tags-connections" type="text" class="form-control" name="tags" value="{{ implode(',', $s2t) }}" readonly>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="tags-checkbox" class="col-md-4 col-form-label text-md-right">Tags</label>
                        @foreach($tags as $t)
                            <div class="form-check form-check-inline tags-checkbox">
                                <input class="form-check-input tags-check" type="checkbox" id="tag-{{$t->id}}" value="{{$t->id}}" {{in_array($t->id, $s2t) ? 'checked' : ''}}>
                                <label class="form-check-label" for="tag-{{$t->id}}">{{$t->key}}</label>
                            </div>
                        @endforeach
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

    <script>
    (function(){

        $(document).on('click', '.categories-checkbox > input', function(){
            let categories = [];
            $('.categories-check:checkbox:checked').each(function() {
                categories.push($(this).val())
            });
            $('#categories-connections').val(categories.join(','));
        });

        $(document).on('click', '.tags-checkbox > input', function(){
            let tags = [];
            $('.tags-check:checkbox:checked').each(function() {
                tags.push($(this).val())
            });
            $('#tags-connections').val(tags.join(','));
        });

    })(jQuery)
    </script>

@endsection



