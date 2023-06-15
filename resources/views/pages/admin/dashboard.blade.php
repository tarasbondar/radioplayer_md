@extends('layouts.admin')

@section('content')

    <div class="container">

        @foreach($stats as $k => $v)
            {{ "$k: $v"  }}<br>
        @endforeach

    </div>

@endsection()
