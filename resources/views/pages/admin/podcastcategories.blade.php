@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Podcast Categories</h2>

        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
                <a href="/admin/podcast-categories/add" class="btn btn-primary" type="button">Add podcast category</a>
            </div>
        </div>

        @if (!empty($categories))
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Key</th>
                    <th scope="col">Status</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($categories as $c)
                    <tr>
                        <th scope="row">{{ $c['id'] }}</th>
                        <td>{{ $c['key'] }}</td>
                        <td>{{ $c['status'] == 0 ? 'Active' : 'Inactive' }}</td>
                        <td>{{ $c['created_at'] }}</td>
                        <td>
                            <a href="#" class="edit-category edit-{{ $c['id'] }}">Edit</a>
                            <a href="#" class="remove-category remove-{{ $c['id'] }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif


        <script>
            (function(){
                $(document).on('click', '.edit-category', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let category_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('edit-') >= 0) {
                            category_id = v.split("-")[1];
                        }
                    });

                    window.location.href = '/admin/podcast-categories/edit/' + category_id;

                });

                $(document).on('click', '.remove-category', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let category_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('remove-') >= 0) {
                            category_id = v.split("-")[1];
                        }
                    });

                    if (confirm('Are you sure?')) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '/admin/podcast-categories/' + category_id,
                            method: 'DELETE',
                            success: function (response) {
                                window.location.href = '/admin/podcast-categories';
                            }
                        });
                    }
                });

            })(jQuery)
        </script>
    </div>
@endsection


