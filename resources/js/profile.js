$("#new-chat-form").submit(function(e) {
    e.preventDefault();
    $.ajax({
        type:'post',
        url:'/chats/',
        headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        data:{
            user_ids:[window.profile_user_id],
            message:$('#new-chat-msg').val()
        },
        success: function(res) {
            window.location = "/chats";
        },
        error:function(err) {
            console.log(err.responseText);
        }
    });
});