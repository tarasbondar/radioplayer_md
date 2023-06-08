@extends('layouts.admin')

@section('content')

    @foreach($stats as $k => $v)
        {{ "$k: $v"  }}<br>
    @endforeach

@endsection()
