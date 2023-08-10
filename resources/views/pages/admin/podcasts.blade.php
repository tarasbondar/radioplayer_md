@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Podcasts</h2>

        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
                <a href="/admin/podcasts/add" class="btn btn-primary" type="button">Create</a>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4"> <input id="name" type="text" class="form-control" name="name" value="{{ app('request')->input('name') }}" placeholder="Name"> </div>
            <div class="col-md-5"> <input id="description" type="text" class="form-control" name="description" value="{{ app('request')->input('descr') }}" placeholder="Description"> </div>
            <div class="col-md-3 row">
                <div class="col-md-5"><button id="apply-filters" class="btn btn-lg btn-primary" >Apply</button> </div>
                <div class="col-md-5"> <a id="reset-filters" class="btn btn-lg" href="/admin/podcasts">Reset</a> </div>
            </div>
        </div>

        @if (!empty($podcasts))
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Author</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($podcasts as $p)
                    <tr>
                        <th scope="row">{{ $p['id'] }}</th>
                        <td>{{ $p['name'] }}</td>
                        <td>
                            @if(strlen($p['description'] > 100))
                                {{ substr($p['description'], 0, 95) . '...' }}
                            @else
                                {!! $p['description'] !!}
                            @endif
                        </td>
                        <td> <a href="/admin/users/view/{{$p['owner_id']}}" target="_blank"> {{ $p['username'] }} </a> </td>
                        <td>{{ $p['status'] == 0 ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="/admin/podcasts/edit/{{ $p['id'] }}">Edit</a>
                            <a href="#" class="remove-podcast remove-{{ $p['id'] }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $pagination !!}

            <div class="row">
                <div class="col-md-12 text-right mt-3 mb-3 align-items-end clearfix">
                    <a href="javascript:void(0)" class="btn btn-success float-end" id="podcasts-download" type="button">Download</a>
                </div>
            </div>

        @else
            {{ 'List is empty' }}
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

                    if (confirm('Are you sure?')) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '/admin/podcasts/' + podcast_id,
                            method: 'DELETE',
                            success: function (response) {
                                window.location.href = '/admin/podcasts';
                            }
                        });
                    }
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
                        window.location.href = '/admin/podcasts' + uri;
                    }
                });

                $(document).on('click', '#podcasts-download', function() {
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
                        url: '/admin/podcasts/download',
                        method: 'GET',
                        success: function (response) {
                            let blob = new Blob([response]);
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            let dt = new Date(); //$.now()
                            let time = dt.getFullYear() + '_' + (dt.getMonth() + 1) + '_' + dt.getDate() + '_' + dt.getHours() + "_" + dt.getMinutes() + "_" + dt.getSeconds();
                            link.download = 'podcasts_' + time + '.xlsx';
                            link.click();
                        }
                    })
                });

            })(jQuery)
        </script>
    </div>
@endsection

