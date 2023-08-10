@extends('layouts.admin')

@section('content')

    <div class="container">
        <h2>User info</h2>

        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Name</label>
                    <div class="col-md-6 py-2"> <span> {{$user['name']}} </span> </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Email</label>
                    <div class="col-md-6 py-2"> <span> {{$user['email']}} </span> </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Role</label>
                    <div class="col-md-6 py-2"> <span> {{ ($user['role'] == 0 ? 'User' : ($user['role'] == 1 ? 'Author' : 'Admin')) }} </span> </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Status</label>
                    <div class="col-md-6 py-2"> <span> {{$user['status'] == 0 ? 'Активный' : 'Заблокирован'}} </span> </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Language</label>
                    <div class="col-md-6 py-2"> <span> {{$user['language']}} </span> </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Registration date</label>
                    <div class="col-md-6 py-2"> <span> {{$user['created_at']}} </span> </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Last login date</label>
                    <div class="col-md-6 py-2"> <span> {{$user['last_login_at']}} </span> </div>
                </div>

                <div class="mb-3 form-group row">
                    @foreach($stats as $k=>$v)
                        {{ "$k: $v" }}<br>
                    @endforeach
                </div>

                {{-- <a href="{{ request()->headers->get('referer') }}" class="btn"> Go back </a> --}}
            </div>
        </div>
    </div>
@endsection
