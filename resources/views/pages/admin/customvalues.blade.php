@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Особые переменные</h2>

        <div class="row">
            <div class="col-md-12 text-right mt-3 mb-3">
                <a href="/admin/custom-values/add" class="btn btn-primary" type="button">Добавить</a>
            </div>
        </div>

        @if (!empty($values))
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ключ</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($values as $v)
                        <tr>
                            <th scope="row">{{ $v['id'] }}</th>
                            <td>{{ $v['key'] }}</td>
                            <td>
                                <a href="/admin/custom-values/edit/{{ $v['id'] }}">Править</a>
                                <a href="#" class="remove-value remove-{{ $v['id'] }}">Удалить</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{--{!! $pagination !!}--}}

        @endif

        <script>
            (function(){

                $(document).on('click', '.remove-value', function(){
                    let button = $(this);

                    let classes = button.attr('class').split(" ");
                    let value_id = 0;
                    $.each(classes, function (k, v) {
                        if (v.search('remove-') >= 0) {
                            value_id = v.split("-")[1];
                        }
                    });

                    if (confirm('Are you sure?')) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url:'/admin/custom-values/' + value_id,
                            method: 'DELETE',
                            success: function(response) {
                                window.location.href = '/admin/custom-values';
                            }
                        });
                    }

                });

            })(jQuery)
        </script>
    </div>
@endsection

