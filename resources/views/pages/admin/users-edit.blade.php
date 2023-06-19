@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>{{ucfirst($action)}} User</h2>

        <div class="row justify-content-center">
            <div class="col-md-10">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="/admin/users/save" enctype='multipart/form-data'>
                    @csrf
                    <div class="form-group row" hidden>
                        <input id="id" type="text" class="form-control" name="id" value="{{ @$model['id'] }}" readonly>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Имя</label>
                        <div class="col-md-6"> <input id="name" type="text" class="form-control" name="name" value="{{ @$model['name'] }}" required> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                        <div class="col-md-6"> <input id="name" type="text" class="form-control" name="email" value="{{ @$model['email'] }}" required> </div>
                    </div>

                    <div class="mb-3 form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>
                        <div class="col-md-6"> <input id="password" type="text" class="form-control" name="password" value="" {{ ($action === 'add') ? 'required' : ''  }}> </div>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="status" class="col-md-4 col-form-label text-md-right">Роль</label>
                        <div class="col-md-6">
                            <select class="form-select" name="role" id="status" aria-label="Select role">
                                @foreach($roles as $role => $roleLabel)
                                    <option value="{{ $role }}"> {{ $roleLabel }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 form-group row">
                        <label for="status" class="col-md-4 col-form-label text-md-right">Статус</label>
                        <div class="col-md-6">
                            <select class="form-select" name="status" id="status" aria-label="Select status">
                                @foreach($statuses as $status => $statusLabel)
                                    <option value="{{ $status }}"> {{ $statusLabel }} </option>
                                @endforeach
                            </select>
                        </div>
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



