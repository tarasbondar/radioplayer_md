@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} Radio Station Group</h2>

        <div class="row justify-content-center">
            <div class="col-md-10">

                <form method="POST" action="/admin/station-groups/save" enctype = 'multipart/form-data'>
                    @csrf
                    <div class="form-group row" hidden>
                        <input id="id" type="text" class="form-control" name="id" value="{{ @$group['id'] }}" readonly>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="key" class="col-md-4 col-form-label text-md-right">Ключ</label>
                        <div class="col-md-6"> <input id="key" type="text" class="form-control" name="key" value="{{ @$group['key'] }}" required> </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary"> Сохранить </button>
                            <a href="{{ request()->headers->get('referer') }}" class="btn"> Отклонить </a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection



