@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Мета тэги</h2>

        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
                <a href="/admin/meta-tags/add" class="btn btn-primary" type="button">Add</a>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4"> <input id="route" type="text" class="form-control" name="route" value="{{ app('request')->input('route') }}" placeholder="Route"> </div>
            <div class="col-md-3 row">
                <div class="col-md-5"><button id="apply-filters" class="btn btn-lg btn-primary">Apply</button> </div>
                <div class="col-md-5"> <a id="reset-filters" class="btn btn-lg" href="/admin/meta-tags">Reset</a> </div>
            </div>
        </div>

        @if (!empty($models))
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Path</th>
                    <th scope="col">Name</th>
                    <th scope="col">Keywords</th>
                    <th scope="col">Description</th>
                    <th scope="col">Default value</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($models as $model)
                    <tr>
                        <th scope="row">{{ $model->id }}</th>
                        <td>{{ $model->route }}</td>
                        <td>{!! $model->getTranslation('meta_title') !!}</td>
                        <td>{!! $model->getTranslation('meta_keywords') !!}</td>
                        <td>{!! $model->getTranslation('meta_description') !!}</td>
                        <td>{{ $model->is_default ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="/admin/meta-tags/edit/{{ $model->id }}">Edit</a>
                            <a href="#" class="remove-meta-tag remove-{{ $model->id }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $pagination !!}

        @else
            {{ 'List is empty' }}
        @endif


        <script>
            (function(){
                $(document).on('click', '.remove-meta-tag', function(){
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
                            url: '/admin/meta-tags/' + podcast_id,
                            method: 'DELETE',
                            success: function (response) {
                                window.location.href = '/admin/meta-tags';
                            }
                        });
                    }
                });

                $(document).on('click', '#apply-filters', function () {
                    let route = $('#route').val();

                    let params = [];
                    let uri = '';
                    params.push('route=' + route);

                    let searchParams = new URLSearchParams(window.location.search);
                    if (searchParams.has('page')) {
                        params.push('page=' + searchParams.get('page'));
                    }

                    if (params.length > 0) {
                        uri = '?' + params.join('&');
                        window.location.href = '/admin/meta-tags' + uri;
                    }
                });

            })(jQuery)
        </script>
    </div>
@endsection

