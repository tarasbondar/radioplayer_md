@extends('layouts/admin')

@section('content')

    <div class="container">

        <h2>Podcasts Stats</h2>

        <div class="row justify-content-left">
            <div class="col-md-12 row mb-3">
                <div class="col-md-4 mb-3">
                    <label for="from">From: </label>
                    <input id="from" class="form-control" value="{{ app('request')->input('from') }}"/>
                </div>
                <div class="col-md-4">
                    <label for="to">To: </label>
                    <input id="to" class="form-control" value="{{ app('request')->input('to') }}"/>
                </div>
                <div class="col-md-3 row">
                    <div class="col-md-5"> <button id="apply-filters" class="btn btn-lg btn-primary">Apply</button> </div>
                    <div class="col-md-5"> <a id="reset-filters" class="btn btn-lg" href="/admin/podcasts-stats">Reset</a> </div>
                </div>
            </div>

            <div class="row">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Name</th>
                        <th>Play Count</th>
                        <th>Marked to listen later</th>
                        <th>Downloaded</th>
                        <th>Subscribers</th>
                    </tr>
                    @foreach($stats as $stat)
                        <tr>
                            <td>{{$stat['name']}}</td>
                            <td>{{!empty($stat['plays']) ? $stat['plays'] : 0}}</td>
                            <td>{{!empty($stat['later']) ? $stat['later'] : 0}}</td>
                            <td>{{!empty($stat['downloads']) ? $stat['downloads'] : 0}}</td>
                            <td>{{!empty($stat['subs']) ? $stat['subs'] : 0}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>

        </div>

    </div>

    <script>

        (function(){

            const picker_from = new easepick.create({
                element: document.getElementById('from'),
                css: [
                    'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css',
                ],
            });

            const picker_to = new easepick.create({
                element: document.getElementById('to'),
                css: [
                    'https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.css',
                ],
            });

            $(document).on('click', '#apply-filters', function () {
                let from = $('#from').val();
                let to = $('#to').val();
                let params = [];
                let uri = '';

                if (from.length > 1) {
                    params.push('from=' + from);
                }

                if (to.length > 1) {
                    params.push('to=' + to);
                }

                if (params.length > 0) {
                    uri = '?' + params.join('&');
                    window.location.href = '/admin/podcasts-stats' + uri;
                }
            });

        })(jQuery)

    </script>

@endsection
