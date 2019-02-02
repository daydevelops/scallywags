window.toggleFavourite = (type,id) => {
	// if button has class of "favourited"
	if ($('#'+type+'-'+id).hasClass('favourited')) {
		// send post to unfavourite
		$.ajax({
			type:'post',
			url:'/unfavourite/'+type+'/'+id,
			headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
			success: function(res) {
				console.log(res);
				// if (res.status==1) {
					$('#'+type+'-'+id).removeClass('favourited')
				// }
			},
			error:function(err) {
				console.log(err.responseText);
			}
		});
	} else {
		// send post to favourite
		$.ajax({
			type:'post',
			url:'/favourite/'+type+'/'+id,
			headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
			success: function(res) {
				console.log(res);
				// if (res.status==1) {
					$('#'+type+'-'+id).addClass('favourited')
				// }
			},
			error:function(err) {
				console.log(err.responseText);
			}
		});
	}
}
