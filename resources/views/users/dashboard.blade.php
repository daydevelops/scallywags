@extends('layouts/main')

@section('css')
<link rel='stylesheet' href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')

<header>
    <h1 class='text-center'>{{$user->name}}
        @if ($user->skill !== "NA")
        <span class='user-skill'>[{{$user->skill}}]</span>
        @endif
    </h1>
</header>
<section id='personal-info-wrapper'>
    <div class='container-fluid'>
        <div class='row'>
            <div class='col-sm-6' id='profile-image-wrapper'>
                <img id='user-image' src='{{$user->image}}'>
            </div>
            <div class='col-sm-6' id='personal-info'>
                <p id='user-name'><b>Name:</b> {{$user->name}}</p>
                @if ($user->skill !== "NA")
                <p id='user-skill'><b>Skill Level:</b> {{$user->skill}}</p>
                @endif
                <p id='user-email'><b>Email Addresss:</b> {{$user->email}}</p>

            </div>
        </div>
        <div class='row'>
            <div class='col-12'>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit-profile-modal">
                    Edit Profile
                </button>
            </div>
        </div>
    </div>
</section>
<hr>
<section id='membership-section'>
    <div class='container-fluid'>
        <div class='row'>
            <div class='col-12 text-center'>
                <p>Membership Status: <span id='membership-status'>Up to date :)</span></p>
                <p>Next Renewal date: <span id='renewal-date'>NEVARRR</span></p>
            </div>
        </div>
    </div>
</section>
<hr>
<section 'games-section'>
    <div class='container-fluid'>
        <div class='row'>
            <div class='col-md-6'>
                <h3>Upcomming Games</h3>
                <table id='ugames-table' class='table'>
                    <thead>
                        <tr>
                            <th scope='col'>Date</th>
                            <th scope='col'>Competition</th>
                            <th scope='col'>public/private</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($games as $g)
                        <tr class='user-game' id='user-game-{{$g->id}}' onclick='showGameModal({{$g->id}})'>
                            <td scope='col' class='game-date'>{{$g->gamedate}}</td>
                            <td scope='col' class='game-comp'>
                                @if (sizeof($g->users)>1)
                                {{$g->users[0]->name}} and {{sizeof($g->users)-1}} other
                                @else
                                {{$g->users[0]->name}}
                                @endif
                            </td>
                            <td scope='col' class='game-type {{$g->private?"":"public-game"}}'>
                                {{$g->private?"private":"public"}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</section>


<!-- Modal -->
<div class="modal fade" id="edit-profile-modal" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="form-name">User Name</label>
                        <input type='text' class='form-control' id='form-name' name='name' value='{{$user->name}}'>
                    </div>
                    <div>
                        <label for="form-email">Email Address</label>
                        <input type="email" class="form-control" id="form-email" aria-describedby="emailHelp" value="{{$user->email}}">
                    </div>
                    <div class="form-group">
                        <label for="old-pass">Password</label>
                        <input type="password" class="form-control" id="old-pass" placeholder="Old Password">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="new-pass" placeholder="New Password">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="confirm-pass" placeholder="Confirm Password">
                    </div>
                    <div class="form-group">
                        <label for="form-skill">Skill Level</label>
                        <select class="form-control" id="exampleFormControlSelect1">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>I dont know</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" data-dismiss="modal">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal for User Games-->
<div class="modal fade" id="game-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3>Date: <span id='gm-date'></span></h3>
                <p><i><small>To reschedule your game, visit the <a href='/games'>schedule</a></small></i></p>

                <p><input id='gm-private-check' type='checkbox' onclick='toggleGamePrivate()'> This game is Private </p>
                <div class='row'>
                    <div class='col col-6'>
                        <h4 class='text-left'>Players</h4>
                    </div>
                    <div class='col col-6 text-right'>
                        <button class='btn btn-danger' onclick='leaveGame()'>Leave Game</button></h4>
                    </div>
                </div>
                <div class='row'>
                    <div class='col col-12 text-center'>
                        <ol id='gm-players'>

                        </ol>
                    </div>
                </div>
                <div class='row'>
                        <div class='col col-6'>
                            <h4 class='text-left'>Invites</h4>
                        </div>
                        <div class='col col-6 text-right'>
                            <button class='btn btn-primary' onclick='toggleInviteInput()'>Invite</button>
                        </div>
                        <ol id='gm-invites'>

                        </ol>
                </div>
                <div class='row hidden' id='invite-player-row'>
                    <div class='col col-8'>
                        <div class="form-group">
                            <select class="form-control" id="invite-players-list">
                                @foreach ($users as $u)
                                @if (auth()->id() !== $u->id)
                                <option value='{{$u->id}}'>{{$u->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class='col col-4'>
                        <button class='btn btn-success' onclick='invitePlayer()'>Send Invite</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src='{{ asset("js/dashboard.js") }}'></script>
@endsection
