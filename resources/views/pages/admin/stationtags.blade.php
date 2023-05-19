@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Radio Station Tags</h2>

        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
                <a href="/admin/station-tags/add" class="btn btn-primary" type="button">Add Radio Station Tag</a>
            </div>
        </div>

        @if (!empty($tags))
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Key</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($tags as $t)
                    <tr>
                        <th scope="row">{{ $t['id'] }}</th>
                        <td>{{ $t['key'] }}</td>
                        <td>{{ ($t['status'] === 0 ? 'Active' : 'Inactive' ) }}</td>
                        <td>
                            <a href="#" class="edit-tag edit-{{ $t['id'] }}">Edit</a>
                            <a href="#" class="remove-tag remove-{{ $t['id'] }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $pagination !!}

        @endif

        <script>
            (function(){
                $(document).on('click', '.edit-tag', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let tag_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('edit-') >= 0) {
                            tag_id = v.split("-")[1];
                        }
                    });

                    window.location.href = '/admin/station-tags/edit/' + tag_id;

                });

                $(document).on('click', '.remove-tag', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let tag_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('remove-') >= 0) {
                            tag_id = v.split("-")[1];
                        }
                    });

                    if (confirm('Are you sure?')) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url:'/admin/station-tags/' + tag_id,
                            method: 'DELETE',
                            success: function(response) {
                                window.location.href = '/admin/station-tags';
                            }
                        });
                    }

                });

            })(jQuery)
        </script>
    </div>
@endsection

