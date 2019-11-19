@extends('layouts/main')

@section('content')
<chats :chats="{{$chats}}">
</chats>

@endsection