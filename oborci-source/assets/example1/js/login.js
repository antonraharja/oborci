$(document).ready(function() {
	$('#login_submit').click(function(event) {
		var user = $('#login_username').val();
		var pass = $('#login_password').val();
		event.preventDefault();
		$.post('login/ajax', $('#login_form').serialize(), function(data) {
			result = eval('(' + data + ')');
			if (result.state) {
				setTimeout(go_home, 1000);
			} else {
				$('#login_box_msg').show();
				$('#login_box_msg').html(result.message);
				$('#login_box_msg').fadeOut(10000);
				$('#login_box').fadeIn(2000);
			}
		}), 'json'
	});
	return false;
});

function go_home() {
	window.location.href = '..';
	return false;
}