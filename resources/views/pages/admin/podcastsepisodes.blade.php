@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Эпизоды подкастов</h2>

        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
                <a href="/admin/podcasts-episodes/add" class="btn btn-primary" type="button">Добавить</a>
            </div>
        </div>

        @if (!empty($episodes))
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Подкаст</th>
                    <th scope="col">Название эпизода</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($episodes as $e)
                    <tr>
                        <th scope="row">{{ $e['id'] }}</th>
                        <td><a href="/admin/podcasts/edit/{{$e['podcast_id']}}" target="_blank">{{ $e['podcast_name'] }}</a></td>
                        <td>{{ $e['name'] }}</td>
                        <td>
                            @switch($e['status'])
                                @case(0) {{ 'заблокирован' }} @break
                                @case(1) {{ 'черновик' }} @break
                                @case(2) {{ 'опубликован' }} @break
                            @endswitch
                        </td>
                        <td>
                            <a href="#" class="edit-episode edit-{{ $e['id'] }}">Править</a>
                            <a href="#" class="remove-episode remove-{{ $e['id'] }}">Удалить</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $pagination !!}

        @endif


        <script>
            (function(){
                $(document).on('click', '.edit-episode', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let episode_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('edit-') >= 0) {
                            episode_id = v.split("-")[1];
                        }
                    });

                    window.location.href = '/admin/podcasts-episodes/edit/' + episode_id;

                });

                $(document).on('click', '.remove-episode', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let episode_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('remove-') >= 0) {
                            episode_id = v.split("-")[1];
                        }
                    });

                    if (confirm('Are you sure?')) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '/admin/podcasts-episodes/' + episode_id,
                            method: 'DELETE',
                            success: function (response) {
                                window.location.href = '/admin/podcasts-episodes';
                            }
                        });
                    }
                });

            })(jQuery)
        </script>
    </div>
@endsection


