@extends('layouts.admin')

@section('content')

    <div class="container">

        <h2>Author Applications</h2>

        <div class="row mb-3">
            <div class="col-md-12 form-check form-switch">
                <input class="form-check-input" type="checkbox" id="apps-enable" value="1" {{ !(isset($apps_enable) && $apps_enable == 0) ? 'checked' : '' }}>
                <label class="form-check-label" for="apps-enable" id="for-apps-enable">
                    @if(isset($apps_enable) && $apps_enable == 0)
                        {{ 'Apps are off' }}
                    @else
                        {{ 'Apps are on' }}
                    @endif
                </label>
            </div>
        </div>

        <div class="row mb-3">

            <div class="col-md-3"> <input id="username" type="text" class="form-control" name="name" value="{{ app('request')->input('username') }}" placeholder="Username"> </div>
            <div class="col-md-4"> <input id="email" type="text" class="form-control" name="description" value="{{ app('request')->input('email') }}" placeholder="Email"> </div>

            <div class="col-md-2">
                <select id="status" class="form-select form-control" aria-label="Status select">
                    <option class="select-option" value="all" {{ empty($appends['status']) || $appends['status'] == 'all' ? 'selected' : '' }}>All</option>
                    <option class="select-option" value="1" {{ @$appends['status'] == 1 ? 'selected' : '' }}>Pending</option>
                    <option class="select-option" value="2" {{ @$appends['status'] == 2 ? 'selected' : '' }}>Accepted</option>
                    <option class="select-option" value="0" {{ @$appends['status'] == 0 ? 'selected' : '' }}>Declined</option>
                    <option class="select-option" value="-1"{{ @$appends['status'] == -1 ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>

            <div class="col-md-3 row">
                <div class="col-md-5"> <button id="apply-filters" class="btn btn-lg btn-primary">Apply</button> </div>
                <div class="col-md-5"> <a id="reset-filters" class="btn btn-lg" href="/admin/author-apps">Reset</a> </div>
            </div>

        </div>

        @if (!empty($apps))
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($apps as $a)
                    <tr>
                        <th scope="row">{{ $a['id'] }}</th>
                        <td>{{ $a['title'] }}</td>
                        <td>{{ $a['username'] }}</td>
                        <td>{{ $a['email'] }}</td>
                        <td>
                            <a href="#" class="review id-{{ $a['id'] }}">View</a>
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

            $(document).on('change', '#apps-enable', function(){
                console.log();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/author-apps-enable',
                    success: function(response){
                        let label = $('#for-apps-enable');
                        if (response == 1) {
                            label.html('Apps are enabled');
                        } else {
                            label.html('Apps are disabled');
                        }
                    }
                });
            });

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

            $(document).on('click', '#apply-filters', function () {
                let username = $('#username').val();
                let email = $('#email').val();
                let status = $('.select-option:checked').val();
                let params = [];
                let uri = '';

                if (username.length > 3) {
                    params.push('username=' + username);
                }

                if (email.length > 3) {
                    params.push('email=' + email);
                }

                params.push('status=' + status);

                let searchParams = new URLSearchParams(window.location.search);
                if (searchParams.has('page')) {
                    params.push('page=' + searchParams.get('page'));
                }

                if (params.length > 0) {
                    uri = '?' + params.join('&');
                    window.location.href = '/admin/author-apps' + uri;
                }
            })

        })(jQuery);

    </script>

@endsection
