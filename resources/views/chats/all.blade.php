@extends('layouts/main')

@section('content')
<chats :initial_chats="{{$chats}}">
</chats>

@endsection