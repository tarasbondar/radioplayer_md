@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Author Applications</h2>

        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
                <a href="/admin/author-apps?status={{ \App\Models\AuthorApplication::STATUS_PENDING }}" class="btn btn-primary" type="button">Pending</a>
                <a href="/admin/author-apps?status={{ \App\Models\AuthorApplication::STATUS_APPROVED }}" class="btn btn-success" type="button">Approved</a>
                <a href="/admin/author-apps?status={{ \App\Models\AuthorApplication::STATUS_DECLINED }}" class="btn btn-warning" type="button">Declined</a>
                <a href="/admin/author-apps?status={{ \App\Models\AuthorApplication::STATUS_NO_RETRY }}" class="btn btn-danger" type="button">Blocked</a>
            </div>
        </div>

        @if (!empty($apps))
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th> {{--replace with usernames--}}
                    <th scope="col">Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($apps as $a)
                    <tr>
                        <th scope="row">{{ $a['id'] }}</th>
                        <td>{{ $a['title'] }}</td>
                        <td>{{ $a['description'] }}</td>
                        <td>
                            @if ($a['status'] == \App\Models\AuthorApplication::STATUS_PENDING )
                                <a href="#" class="review id-{{ $a['id'] }}">Review</a>
                            @else
                                <a href="#" class="review id-{{ $a['id'] }}">View</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $pagination !!}

        @endif

    </div>

    <script>
        (function() {

            $(document).on('click', '.review', function () {
                let button = $(this);

                let classes = button.attr('class').split(" ");
                let app_id = 0;
                $.each(classes, function (k, v) {
                    if (v.search('id-') >= 0) {
                        app_id = v.split("-")[1];
                    }
                });

                window.location.href = '/admin/author-apps-review/' + app_id;

            });
        })(jQuery);

    </script>

@endsection
