@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Radiostations</h2>

        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
                <a href="/admin/stations/add" class="btn btn-primary" type="button">Add</a>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4"> <input id="name" type="text" class="form-control" name="name" value="{{ app('request')->input('name') }}" placeholder="Name"> </div>
            <div class="col-md-5"> <input id="description" type="text" class="form-control" name="description" value="{{ app('request')->input('descr') }}" placeholder="Description"> </div>
            <div class="col-md-3 row">
                <div class="col-md-5"><button id="apply-filters" class="btn btn-lg btn-primary">Apply</button></div>
                <div class="col-md-5"><a id="reset-filters" class="btn btn-lg" href="/admin/stations">Reset</a></div>
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
                        <td>{!! $s['description'] !!}</td>
                        <td>
                            <a href="#" class="edit-station edit-{{ $s['id'] }}">Edit</a>
                            <a href="#" class="remove-station remove-{{ $s['id'] }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $pagination !!}

            <div class="row">
                <div class="col-md-12 text-right mt-3 mb-3 align-items-end clearfix">
                    <a href="javascript:void(0)" class="btn btn-success float-end" id="stations-download" type="button">Download</a>
                </div>
            </div>

        @else
            {{ 'List is empty' }}
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
                        xhrFields: {
                            responseType: "blob",
                        },
                        data: {
                            name: $('#name').val(),
                            descr: $('#description').val()
                        },
                        url: '/admin/stations/download',
                        method: 'GET',
                        success: function (response) {
                            let blob = new Blob([response]);
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            let dt = new Date(); //$.now()
                            let time = dt.getFullYear() + '_' + (dt.getMonth() + 1) + '_' + dt.getDate() + '_' + dt.getHours() + "_" + dt.getMinutes() + "_" + dt.getSeconds();
                            link.download = 'radiostations_' + time + '.xlsx';
                            link.click();
                        }
                    })
                });

                $(document).on('click', '#apply-filters', function () {
                    let name = $('#name').val();
                    let descr = $('#description').val();
                    let params = [];
                    let uri = '';

                    if (name.length > 3) {
                        params.push('name=' + name);
                    }

                    if (descr.length > 3) {
                        params.push('descr=' + descr);
                    }

                    let searchParams = new URLSearchParams(window.location.search);
                    if (searchParams.has('page')) {
                        params.push('page=' + searchParams.get('page'));
                    }

                    if (params.length > 0) {
                        uri = '?' + params.join('&');
                        window.location.href = '/admin/stations' + uri;
                    }
                })

            })(jQuery)
        </script>
    </div>
@endsection


