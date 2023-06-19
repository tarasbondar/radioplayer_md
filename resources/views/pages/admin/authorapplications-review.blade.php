@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>
            @switch ($app['status'])
                @case(-1) {{ 'Заблокированные' }} @break
                @case(0) {{ 'Отменённые' }} @break
                @case(1) {{ 'На рассмотрении' }} @break
                @case(2) {{ 'Принятые' }} @break
            @endswitch
            Application
        </h2>

        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Имя пользователя</label>
                    <div class="col-md-6 py-2"> <span> <a href="/admin/users/view/{{$user['id']}}" target="_blank">{{ $user['name'] }}</a> </span> </div>
                </div>

                <div class="mb-3 form-group row">
                    <label for="title" class="col-md-4 col-form-label text-md-right">Название</label>
                    <div class="col-md-6"> <input id="title" type="text" class="form-control" name="title" value="{{ @$app['title'] }}" readonly> </div>
                </div>

                <div class="mb-3 form-group row">
                    <label for="description" class="col-md-4 col-form-label text-md-right">Описание</label>
                    <div class="col-md-6"> <textarea id="description" type="text" class="form-control" name="description" readonly>{{ @$app['description'] }}</textarea> </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="categories-input" >Категории</label>
                    <div class="col-md-6 py-2"> <input id="categories-input" class="form-control" value="{{ $categories }}" readonly></div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="image" >Обложка</label>
                    <div class="col-md-6 py-2">
                        <img id="image" alt="" src="/uploads/applications_images/{{ $app['image'] }}" width="325" height="325"/>
                    </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="audio" >Аудио</label>
                    <div class="col-md-6 py-2">
                        <audio id="audio" controls class="form-control"><source src="/uploads/applications_audio/{{ $app['example'] }}"></audio>
                    </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Статус</label>
                    <div class="col-md-6 py-2"> <span> {{ (@$app['status'] == 2 ? 'Принято' : ($app['status'] == 1 ? 'На рассмотрении' : 'Отклонено') ) }} </span>  </div>
                </div>

                <div class="mb-3 form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Время ревью</label>
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
                        <button class="btn submit-app btn-success" value="2"> Принять </button>
                        <button class="btn submit-app btn-warning" value="0"> Отклонить </button>
                        <button class="btn submit-app btn-danger" value="-1"> Заблокировать </button>
                        <a href="{{ request()->headers->get('referer') }}" class="btn"> Назад </a>
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
