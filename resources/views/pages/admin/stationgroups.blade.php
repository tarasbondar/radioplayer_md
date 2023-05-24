@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Radio Station Groups</h2>

        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
                <a href="/admin/station-groups/add" class="btn btn-primary" type="button">Add Radio Station Group</a>
            </div>
        </div>

        @if (!empty($groups))
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Key</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <th scope="row">{{ $group['id'] }}</th>
                        <td>{{ $group['key'] }}</td>
                        <td>{{ $group['created_at'] }}</td>
                        <td>
                            <a href="#" class="edit-group edit-{{ $group['id'] }}">Edit</a>
                            <a href="#" class="remove-group remove-{{ $group['id'] }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $pagination !!}

        @endif

        <script>
            (function(){
                $(document).on('click', '.edit-group', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let group_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('edit-') >= 0) {
                            group_id = v.split("-")[1];
                        }
                    });

                    window.location.href = '/admin/station-groups/edit/' + group_id;

                });

                $(document).on('click', '.remove-group', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let group_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('remove-') >= 0) {
                            group_id = v.split("-")[1];
                        }
                    });

                    if (confirm('Are you sure?')) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url:'/admin/station-groups/' + group_id,
                            method: 'DELETE',
                            success: function(response) {
                                window.location.href = '/admin/station-groups';
                            }
                        });
                    }

                });

            })(jQuery)
        </script>
    </div>
@endsection

