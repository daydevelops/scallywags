window.showReplyForm = () => {
	$('#new-reply-wrap').removeClass('hidden');
	$('#new-reply-btn').addClass('hidden');
}
window.hideReplyForm = () => {
	$('#new-reply-wrap').addClass('hidden');
	$('#new-reply-btn').removeClass('hidden');
}
window.showAYSM = (action,item_type,item_id,url) => {
	$('#aysm-action').html(action+' this '+item_type);
	$('#aysm-yes-btn').html('DELETE');
	$('#aysm-yes-btn').on('click',() => {
		$.ajax({
			type:'DELETE',
			url:url,
			headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
			success: function(res) {
				console.log(res);
				if (res.status==1) {
					if (item_type == 'thread') {
						window.location = '/forum';
					} else if (item_type == 'reply') {
						location.reload();
					}
				}
			},
			error:function(err) {
				console.log(err.responseText);
			}
		});
	});
	$('#aysm').modal('show');
}

window.closeAYSM = () => {
	$('#aysm-yes-btn').off('click');
}
