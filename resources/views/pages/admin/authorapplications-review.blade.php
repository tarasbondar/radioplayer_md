@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>
            @switch ($app['status'])
                @case(-1) {{ 'Blocked' }} @break
                @case(0) {{ 'Declined' }} @break
                @case(1) {{ 'Pending' }} @break
                @case(2) {{ 'Accepted' }} @break
            @endswitch
            Application
        </h2>

        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Username</label>
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

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="categories-input">Categories</label>
                    <div class="col-md-6 py-2"> <input id="categories-input" class="form-control" value="{{ $categories }}" readonly></div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="image">Image</label>
                    <div class="col-md-6 py-2">
                        <img id="image" alt="" src="/uploads/applications_images/{{ $app['image'] }}" width="325" height="325"/>
                    </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="audio">Audio</label>
                    <div class="col-md-6 py-2">
                        <audio id="audio" controls class="form-control"><source src="/uploads/applications_audio/{{ $app['example'] }}"></audio>
                    </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Status</label>
                    <div class="col-md-6 py-2"> <span> {{ (@$app['status'] == 2 ? 'Accepted' : ($app['status'] == 1 ? 'Pending' : 'Declined') ) }} </span>  </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Review time</label>
                    <div class="col-md-6 py-2"> <span> {{ @$app['updated_at'] }} </span>  </div>
                </div>

                <div class="form-group row" hidden>
                    <input id="app-id" type="text" class="form-control" name="id" value="{{ @$app['id'] }}" readonly>
                </div>
                <div class="mb-3 form-group row">
                    <label for="feedback" class="col-md-4 col-form-label text-md-right">Отзыв</label>
                    <div class="col-md-6"> <textarea id="feedback" type="text" class="form-control" name="feedback" required> {{ @$app['feedback_message'] }} </textarea> </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn submit-app btn-success" value="2"> Accept </button>
                        <button class="btn submit-app btn-warning" value="0"> Decline </button>
                        <button class="btn submit-app btn-danger" value="-1"> Blocked </button>
                        <a href="{{ request()->headers->get('referer') }}" class="btn"> Back </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>

        (function(){

            $(document).on('click', '.submit-app', function(){
                let status = $(this).val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/author-apps-review',
                    data: {
                        id: $('#app-id').val(),
                        status: status,
                        feedback: $("#feedback").val()
                    },
                    method: 'POST',
                    success: function (response) {
                        window.location.href = '/admin/author-apps';
                    }
                });
            });

        })(jQuery);


    </script>

@endsection
