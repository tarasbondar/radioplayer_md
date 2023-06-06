@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} Value</h2>

        <div class="row justify-content-center">
            <div class="col-md-10">

                <form method="POST" action="/admin/custom-values/save">
                    @csrf
                    <div class="form-group row" hidden>
                        <input id="id" type="text" class="form-control" name="id" value="{{ @$value['id'] }}" readonly>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="key" class="col-md-4 col-form-label text-md-right">Key</label>
                        <div class="col-md-6"> <input id="key" type="text" class="form-control" name="key" value="{{ @$value['key'] }}" required> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="value" class="col-md-4 col-form-label text-md-right">Value</label>
                        <div class="col-md-6"> <textarea id="value" type="text" class="form-control ckeditor-custom" name="value" required> {{ @$value['value'] }} </textarea> </div>
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



