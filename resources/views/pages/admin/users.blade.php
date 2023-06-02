@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Users</h2>
        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
                <a href="/admin/users/add" class="btn btn-primary" type="button">Add User</a>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4"> <input id="username" type="text" class="form-control" name="username" value="{{ app('request')->input('username') }}" placeholder="Username"> </div>
            <div class="col-md-5"> <input id="email" type="text" class="form-control" name="email" value="{{ app('request')->input('email') }}" placeholder="Email"> </div>
            <div class="col-md-3 row">
                <div class="col-md-5"><button id="apply-filters" class="btn btn-lg btn-primary">Apply</button> </div>
                <div class="col-md-5"> <a id="reset-filters" class="btn btn-lg" href="/admin/users">Reset</a> </div>
            </div>
        </div>

        @if (!empty($users))
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($users as $u)
                    <tr>
                        <th scope="row">{{ $u['id'] }}</th>
                        <td>{{ $u['name'] }}</td>
                        <td>{{ $u['email'] }}</td>
                        <td>{{ $u['role'] == 2 ? 'Admin' : ($u['role'] == 1 ? 'Author' : 'User') }}</td>
                        <td>
                            <a href="#" class="edit-user edit-{{ $u['id'] }}">Edit</a>
                            <a href="#" class="change-user-status id-{{ $u['id'] }}">{{ ($u['status'] == 1 ? 'Unblock' : 'Block') }}</a>
                            <a href="#" class="view-user view-{{ $u['id'] }}">View details</a>
                            <a href="#" class="remove-user remove-{{ $u['id'] }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $pagination !!}

            <div class="row">
                <div class="col-md-12 text-right mt-3 mb-3 align-items-end clearfix">
                    <a href="javascript:void(0)" class="btn btn-success float-end" id="users-download" type="button">Download</a>
                </div>
            </div>

        @endif

    </div>

    <script>
        (function() {
            $(document).on('click', '.edit-user', function(){
                let button = $(this);

                let classes = button.attr('class').split(" ");
                let id = 0;
                $.each(classes, function (k, v) {
                    if (v.search('edit-') >= 0) {
                        id = v.split("-")[1];
                    }
                });

                window.location.href = '/admin/users/edit/' + id;

            });
            $(document).on('click', '.change-user-status', function () {
                let button = $(this);

                let classes = button.attr('class').split(" ");
                let user_id = 0;
                $.each(classes, function (k, v) {
                    if (v.search('id-') >= 0) {
                        user_id = v.split("-")[1];
                    }
                });

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/users/status',
                    data: {id: user_id},
                    method: 'POST',
                    success: function (response) {
                        button.html(response);
                    }
                });
            });

            $(document).on('click', '.view-user', function(){
                let button = $(this);
                let classes = button.attr('class').split(" ");
                let user_id = 0;
                $.each(classes, function (k, v) {
                    if (v.search('view-') >= 0) {
                        user_id = v.split("-")[1];
                    }
                });

                window.location.href = '/admin/users/view/' + user_id;
            });

            $(document).on('click', '.remove-user', function(){
                let button = $(this);

                let classes = button.attr('class').split(" ");
                let user_id = 0;
                $.each(classes, function (k, v) {
                    if (v.search('remove-') >= 0) {
                        user_id = v.split("-")[1];
                    }
                });

                if (confirm('Are you sure?')) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/admin/users/' + user_id,
                        method: 'DELETE',
                        success: function (response) {
                            window.location.href = '/admin/users';
                        }
                    });
                }
            });

            $(document).on('click', '#users-download', function() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    xhrFields: {
                        responseType: "blob",
                    },
                    data: {
                        username: $('#username').val(),
                        email: $('#email').val()
                    },
                    url: '/admin/users/download',
                    method: 'GET',
                    success: function (response) {
                        let blob = new Blob([response]);
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        let dt = new Date(); //$.now()
                        let time = dt.getFullYear() + '_' + (dt.getMonth() + 1) + '_' + dt.getDate() + '_' + dt.getHours() + "_" + dt.getMinutes() + "_" + dt.getSeconds();
                        link.download = 'users_' + time + '.xlsx';
                        link.click();
                    }
                })
            });

            $(document).on('click', '#apply-filters', function () {
                let username = $('#username').val();
                let email = $('#email').val();
                let params = [];
                let uri = '';

                if (username.length > 3) {
                    params.push('username=' + username);
                }

                if (email.length > 3) {
                    params.push('email=' + email);
                }

                let searchParams = new URLSearchParams(window.location.search);
                if (searchParams.has('page')) {
                    params.push('page=' + searchParams.get('page'));
                }

                if (params.length > 0) {
                    uri = '?' + params.join('&');
                    window.location.href = '/admin/users' + uri;
                }
            })

        })(jQuery)



    </script>
@endsection
