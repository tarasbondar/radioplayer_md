@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Radio Stations</h2>

        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
                <a href="/admin/stations/add" class="btn btn-primary" type="button">Add Radio Station</a>
            </div>
        </div>

        @if (!empty($stations))
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($stations as $s)
                    <tr>
                        <th scope="row">{{ $s['id'] }}</th>
                        <td>{{ $s['name'] }}</td>
                        <td>{{ $s['description'] }}</td>
                        <td>
                            <a href="#" class="edit-station edit-{{ $s['id'] }}">Edit</a>
                            <a href="#" class="remove-station remove-{{ $s['id'] }}">Delete</a>
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

        <script>
            (function(){
                $(document).on('click', '.edit-station', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let station_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('edit-') >= 0) {
                            station_id = v.split("-")[1];
                        }
                    });

                    window.location.href = '/admin/stations/edit/' + station_id;

                });

                $(document).on('click', '.remove-station', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let station_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('remove-') >= 0) {
                            station_id = v.split("-")[1];
                        }
                    });

                    if (confirm('Are you sure?')) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '/admin/stations/' + station_id,
                            method: 'DELETE',
                            success: function (response) {
                                window.location.href = '/admin/stations';
                            }
                        });
                    }
                });

                $(document).on('click', '#stations-download', function() {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/admin/stations/download',
                        method: 'POST'
                    })
                })

            })(jQuery)
        </script>
    </div>
@endsection


