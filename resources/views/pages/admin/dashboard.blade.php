@extends('layouts.admin')

@section('content')

    <div class="container">

        {{--@foreach($stats as $k => $v)
            {{ "$k: $v"  }}<br>
        @endforeach--}}

        <?php
            $clicktime = \Carbon\Carbon::create('2023-06-12 00:00:00');
            var_dump(\Carbon\Carbon::now()->greaterThan($clicktime->addHour())); /**/

        ?>

    </div>

@endsection()
