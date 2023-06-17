@extends('layouts/admin')

@section('content')

    <div class="container">

        <h2>Stations Stats</h2>

        <div class="row justify-content-left">
            <div class="col-md-12 row mb-3">
                <div class="col-md-2">
                    <label for="id">Id: </label>
                    <input id="id" type="text" class="form-control" name="id" value="{{ app('request')->input('id') }}">
                </div>

                <div class="col-md-3">
                    <label for="from">From: </label>
                    <input id="from" class="form-control" value="{{ app('request')->input('from') }}"/>
                </div>
                <div class="col-md-3">
                    <label for="to">To: </label>
                    <input id="to"  class="form-control" value="{{ app('request')->input('to') }}"/>
                </div>
                <div class="col-md-3 mb-3 row">
                    <div class="col-md-5"> <button id="apply-filters-rs-stats" class="btn btn-lg btn-primary">Apply</button> </div>
                    <div class="col-md-5"> <a id="reset-filters" class="btn btn-lg" href="/admin/stations-stats">Reset</a> </div>
                </div>
            </div>

            <div class="row">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Name</th>
                        <th>Plays</th>
                        <th>Favorites Total</th>
                        <th>Favorited</th>
                        <th>Unfavorited</th>
                    </tr>
                    @foreach($stats as $stat)
                        <tr>
                            <td>{{$stat['name']}}</td>
                            <td>{{!empty($stat["plays"]) ? $stat["plays"] : 0}}</td>
                            <td>{{!empty($stat['favs_total']) ? $stat['favs_total'] : 0}}</td>
                            <td>{{!empty($stat['favs']) ? $stat['favs'] : 0}}</td>
                            <td>{{!empty($stat['unfavs']) ? $stat['unfavs'] : 0}}</td>
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

            $(document).on('click', '#apply-filters-rs-stats', function () {
                let id = $('#id').val();
                let from = $('#from').val();
                let to = $('#to').val();
                let params = [];
                let uri = '';

                if (id.length > 0) {
                    params.push('id=' + id);
                }

                if (from.length > 1) {
                    params.push('from=' + from);
                }

                if (to.length > 1) {
                    params.push('to=' + to);
                }

                if (params.length > 0) {
                    uri = '?' + params.join('&');
                    window.location.href = '/admin/stations-stats' + uri;
                }
            });

        })(jQuery)

    </script>

@endsection
