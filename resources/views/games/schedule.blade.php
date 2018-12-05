@extends('layouts/main')

@section('css')
<link rel='stylesheet' href="{{ asset('fullCalendar/fullcalendar.css') }}">
<link rel='stylesheet' href="{{ asset('css/schedule.css') }}">
@endsection

@section('content')
<header>
    <h1>SCHEDULE</h1>

</header>

<section id='calendar-section'>

    <div id='calendar'>
    </div>

</section>


<div class="modal fade" id="game-modal" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class='container-fluid'>
                    <div class='row'>
                        <div class='col-12'>
                            <b><h3 id='game-modal-date' class='text-center'></h3></b>
                        </div>
                    </div>
                    <div class='row'>
                        <div id='court-1-col' class='col-6 court-wrap'>
                            <h4 class='text-center'>Court 1</h4>
                            <div id='court-1-content'>
                                <p class='text-center'>This court is free!</p>
                                <button class='btn btn-lrg btn-primary' onclick='showNewGameModal()'>Book Now</button>
                            </div>
                        </div>
                        <div id='court-2-col' class='col-6 court-wrap'>
                            <h4 class='text-center'>Court 2</h4>
                            <div id='court-2-content'>
                                <p class='text-center'>This court is free!</p>
                                <button class='btn btn-lrg btn-primary' onclick='showNewGameModal()'>Book Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="new-game-modal" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form>
                    <div class='row'>
                        <div class='col-md-6'>
                            <h3>Date: <b><span id='new-game-date'></span></b></h3>
                        </div>
                        <div class='col-md-6'>
                            <p><input id='gm-private-check' name='private' type='checkbox'> This game is Private </p>
                        </div>
                    </div>
                    <hr>
                    <div class='row'>
                        <div class='col-12'>
                            <p>Players:<small><small>You are automatically added to the game</small></small></p>
                            <span id='add-players-btn' onclick='toggleInviteInput()'><i class="fas fa-plus-circle"></i></span>
                            <span id='players-list'>
                            </span>
                        </div>
                    </div>
                    <div class='row hidden' id='invite-player-row'>
                        <div class='col-11'>
                            <div class='col col-12'>
                                <div id="invite-players-list" class='row'>
                                    @foreach ($users as $u)
                                    @if (auth()->id() !== $u->id)
                                    <div class='invite-player-chk col-sm-6'>
                                        <input type='checkbox' id='invite-player-{{$u->id}}' onclick="togglePlayer({{$u->id}});" value='{{$u->name}}'>{{$u->name}}
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class='col-1'>
                            <i class="fas fa-times-circle" onclick='$("#invite-player-row").addClass("hidden")'></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" data-dismiss="modal" onclick='newGame()'>Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js'></script>
<script src='{{ asset("fullCalendar/moment.min.js") }}'></script>
<script src='{{ asset("fullCalendar/fullcalendar.js") }}'></script>
<script src='{{ asset("js/schedule.js") }}'></script>
@endsection
