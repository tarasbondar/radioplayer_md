@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Users</h2>
        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
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
                            <a href="#" class="change-user-status id-{{ $u['id'] }}">{{ ($u['status'] == 1 ? 'Unblock' : 'Block') }}</a>
                            <a href="#" class="view-user view-{{ $u['id'] }}">View details</a>
                            <a href="#" class="remove-user remove-{{ $u['id'] }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $pagination !!}

            {{--<div class="row">
                <div class="col-md-12 text-right mt-3 mb-3 align-items-end clearfix">
                    <a href="#" class="btn btn-success float-end" id="stations-download" type="button">Download</a>
                </div>
            </div>--}}

        @endif

    </div>

    <script>
        (function() {
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

        })(jQuery)



    </script>
@endsection
