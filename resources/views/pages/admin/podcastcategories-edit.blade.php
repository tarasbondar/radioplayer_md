@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} Podcast Category</h2>

        <div class="row justify-content-center">
            <div class="col-md-10">

                <form method="POST" action="/admin/podcast-categories/save">
                    @csrf
                    <div class="form-group row" hidden>
                        <input id="id" type="text" class="form-control" name="id" value="{{ @$category['id'] }}" readonly>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="key" class="col-md-4 col-form-label text-md-right">Key</label>
                        <div class="col-md-6"> <input id="key" type="text" class="form-control" name="key" value="{{ @$category['key'] }}" required> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status-active" value="0" {{ empty(@$category['status']) ? 'checked' : ''}}>
                                <label class="form-check-label" for="status-active">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status-inactive" value="1" {{ @$category['status'] == 1 ? 'checked' : ''}}>
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




