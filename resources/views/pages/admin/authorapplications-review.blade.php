@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>
            @switch ($app['status'])
                @case(0) {{ 'Declined' }} @break
                @case(1) {{ 'Pending' }} @break
                @case(2) {{ 'Accepted' }} @break
            @endswitch
            Application
        </h2>

        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">User info</label>
                    <div class="col-md-6 py-2"> <span> <a href="/admin/users/view/{{$user['id']}}" target="_blank">{{ $user['name'] }}</a> </span> </div>
                </div>

                <div class="mb-3 form-group row">
                    <label for="title" class="col-md-4 col-form-label text-md-right">Title</label>
                    <div class="col-md-6"> <input id="title" type="text" class="form-control" name="title" value="{{ @$app['title'] }}" readonly> </div>
                </div>

                <div class="mb-3 form-group row">
                    <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                    <div class="col-md-6"> <textarea id="description" type="text" class="form-control" name="description" readonly>{{ @$app['description'] }}</textarea> </div>
                </div>

                {{--category--}}
                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="categories-input" >Categories</label>
                    <div class="col-md-6 py-2"> <input id="categories-input" class="form-control" value="{{ $categories }}"></div>
                </div>

                {{--image--}}

                {{--example--}}

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Status</label>
                    <div class="col-md-6 py-2"> <span> {{ (@$app['status'] == 2 ? 'Accepted' : ($app['status'] == 1 ? 'Pending' : 'Declined') ) }} </span>  </div>
                </div>

                @if($app['status'] == 1)
                    <div class="form-group row" hidden>
                        <input id="app-id" type="text" class="form-control" name="id" value="{{ @$app['id'] }}" readonly>
                    </div>
                    <div class="mb-3 form-group row">
                        <label for="feedback" class="col-md-4 col-form-label text-md-right">Feedback</label>
                        <div class="col-md-6"> <textarea id="feedback" type="text" class="form-control" name="feedback" required> {{ @$app['feedback'] }} </textarea> </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button class="btn btn-success" id="app-accept"> Accept </button>
                            <button class="btn btn-danger" id="app-decline"> Decline </button>
                            <a href="{{ request()->headers->get('referer') }}" class="btn"> Go back </a>
                        </div>
                    </div>
                @endif

                @if($app['status'] == 0)
                    <div class="mb-3 form-group row">
                        <label for="feedback" class="col-md-4 col-form-label text-md-right">Feedback</label>
                        <div class="col-md-6"> <textarea id="feedback" type="text" class="form-control" name="feedback" readonly> {{ @$app['feedback'] }} </textarea> </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>

        (function(){

            $(document).on('click', '#app-accept', function() {
                //console.log($('#app-id').val());
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/author-apps-review',
                    data: {
                        id: $('#app-id').val(),
                        status: 2

                    },
                    method: 'POST',
                    success: function (response) {
                        window.location.href = '/admin/author-apps';
                    }
                });
            });

            $(document).on('click', '#app-decline', function(){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/author-apps-review',
                    data: {
                        id: $('#app-id').val(),
                        status: 0,
                        feedback: $("#feedback").val()
                    },
                    method: 'POST',
                    success: function (response) {
                        window.location.href = '/admin/author-apps';
                    }
                });
            });

        })(jQuery)


    </script>

@endsection
