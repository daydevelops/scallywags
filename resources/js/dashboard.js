window.showGameModal = function(game_id) {
    // get information for the game selected
    document.querySelector('#gm-players').innerHTML=""; // clear player list
    document.querySelector('#gm-invites').innerHTML=""; // clear player list

    $.ajax({
        type:'get',
        url:'/dashboard/'+game_id,
        success:function(res) {
            console.log(res);
            if (res.status==1) {
                window.game = res.game;
                window.players = res.players;
                $('#gm-date').html(new Date(res.game.gamedate*1000));
                $('#gm-private-check').prop('checked',res.game.private==1)
                res.players.forEach((player,ind) => {
                    let li = document.createElement('li');
                    li.innerHTML=player.name;
                    document.querySelector('#gm-players').appendChild(li);
                });
                res.invites.forEach((invite,ind) => {
                    let li = document.createElement('li');
                    li.innerHTML=invite.name;
                    document.querySelector('#gm-invites').appendChild(li);
                });

            } else {
                console.log(res.fb);
            }
        },
        error:function(err) {
            console.log(err.responseText);
        }
    })
    $('#game-modal').modal('show');
    $('#gm-names')
}

window.toggleGamePrivate = () => {
    // console.log('toggle private');

    $.ajax({
        type:'post',
        url:'/game/'+window.game.id+'/toggleprivate',
        headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        success: function(res) {
            console.log(res);
            $('#user-game-'+window.game.id+' .game-type').html(res.private=="1"?"private":"public");
        },
        error:function(err) {
            console.log(err.responseText);
        }
    });
    // change value in dashboard table
}

window.toggleInviteInput = () => {
    $('#invite-player-row').toggleClass('hidden');
}

window.invitePlayer = () => {
    // console.log('invite: '+$('#invite-players-list').val())
    // send ajax request
    // show success message
    var player = $('#invite-players-list').val();
    $.ajax({
        type:'post',
        url:'/game/'+window.game.id+'/invite/'+player+'',
        headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        success: function(res) {
            console.log(res);
            let li = document.createElement('li');
            li.innerHTML=$('#invite-players-list option[value='+$("#invite-players-list").val()+']').html();
            document.querySelector('#gm-invites').appendChild(li);
        },
        error:function(err) {
            console.log(err.responseText);
        }
    });
    window.toggleInviteInput();
}

window.leaveGame = () => {
    // console.log('leave')
    // send ajax request
    // close modal
    // remove row from dashboard table
}
