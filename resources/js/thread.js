window.showReplyForm = () => {
	$('#new-reply-wrap').removeClass('hidden');
	$('#new-reply-btn').addClass('hidden');
}
window.hideReplyForm = () => {
	$('#new-reply-wrap').addClass('hidden');
	$('#new-reply-btn').removeClass('hidden');
}
