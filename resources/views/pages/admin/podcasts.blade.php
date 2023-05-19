@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Podcasts</h2>

        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
                <a href="/admin/podcasts/add" class="btn btn-primary" type="button">Add podcast</a>
            </div>
        </div>

        @if (!empty($podcasts))
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Owner</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($podcasts as $p)
                    <tr>
                        <th scope="row">{{ $p['id'] }}</th>
                        <td>{{ $p['name'] }}</td>
                        <td>{{ $p['description'] }}</td>
                        <td> <a href="/admin/users/view/{{$p['owner_id']}}" target="_blank"> {{ $p['username'] }} </a> </td>
                        <td>{{ $p['status'] == 0 ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="#" class="edit-podcast edit-{{ $p['id'] }}">Edit</a>
                            <a href="#" class="remove-podcast remove-{{ $p['id'] }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $pagination !!}

        @endif


        <script>
            (function(){
                $(document).on('click', '.edit-podcast', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let podcast_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('edit-') >= 0) {
                            podcast_id = v.split("-")[1];
                        }
                    });

                    window.location.href = '/admin/podcasts/edit/' + podcast_id;

                });

                $(document).on('click', '.remove-podcast', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let podcast_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('remove-') >= 0) {
                            podcast_id = v.split("-")[1];
                        }
                    });

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url:'/admin/podcasts/remove',
                        data: {'podcast_id': podcast_id},
                        method: 'POST',
                        success: function(response) {
                            window.location.href = '/admin/podcasts';
                        }
                    });
                });

            })(jQuery)
        </script>
    </div>
@endsection

