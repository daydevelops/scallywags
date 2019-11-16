@extends('layouts/main')

@section('css')
<link rel='stylesheet' href="{{ asset('css/conversations.css') }}">
@endsection

@section('content')
<conversations :convos="{{$convos}}">
</conversations>


@endsection