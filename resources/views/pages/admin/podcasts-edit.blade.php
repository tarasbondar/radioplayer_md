@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} podcast</h2>

        <div class="row justify-content-center">
            <div class="col-md-10">

                <form method="POST" action="/admin/podcasts/save" enctype = 'multipart/form-data'>
                    @csrf
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
                    <div class="mb-3 form-group row"> {{-- Text editor here --}}
                        <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                        <div class="col-md-6"> <textarea id="description" type="text" class="form-control " name="description" rows="6" required> {{ @$podcast['description'] }} </textarea> </div>
                    </div>

                    <div class="input">
                        <div class="input__editor" data-editor></div>
                    </div>

                    @if ($action == 'edit')
                        <div class="mb-3 form-group row">
                            <label for="image" class="col-md-4 col-form-label text-md-right">Image</label>
                            <div class="col-md-6"> {{ @$podcast['username'] }} </div>
                        </div>
                    @endif

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
