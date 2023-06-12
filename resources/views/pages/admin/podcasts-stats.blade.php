@extends('layouts/admin')

@section('content')

    <div class="container">

        <h2>Podcasts Stats</h2>

        <div class="row justify-content-left">
            <div class="col-md-12 row">
                <div class="col-md-4">
                    <div class="input-group date datepicker">
                        <input type="text" class="form-control" name="from" id="from" value="{{ app('request')->input('from') }}"/>
                        <span class="input-group-append">
                          <span class="input-group-text bg-light d-block">
                            <i class="fa fa-calendar"></i>
                          </span>
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group date datepicker">
                        <input type="text" class="form-control" name="to" id="to" value="{{ app('request')->input('to') }}"/>
                        <span class="input-group-append">
                          <span class="input-group-text bg-light d-block">
                            <i class="fa fa-calendar"></i>
                          </span>
                        </span>
                    </div>
                </div>
                <div class="col-md-3 row">
                    <div class="col-md-5"> <button id="apply-filters" class="btn btn-lg btn-primary">Apply</button> </div>
                    <div class="col-md-5"> <a id="reset-filters" class="btn btn-lg" href="/admin/podcasts-stats">Reset</a> </div>
                </div>
            </div>

            <div>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Play Count</th>
                        <th>Marked to listen later</th>
                        <th>Downloaded</th>
                    </tr>
                    @foreach($stats as $stat)
                        <tr>
                            <td>{{$stat['name']}}</td>
                            <td>{{!empty($stat['plays']) ? $stat['plays'] : 0}}</td>
                            <td>{{!empty($stat['later']) ? $stat['later'] : 0}}</td>
                            <td>{{!empty($stat['downloads']) ? $stat['downloads'] : 0}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>

        </div>

    </div>

    <script>

        (function(){
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

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });

        })(jQuery)

    </script>

@endsection
