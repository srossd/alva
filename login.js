$(function () {
    $("#logintabs").tabs();
    $(document).tooltip();
	$(".shortcut").bind('keydown', 'return', action);
	$("#name").keyup(function() {
		name = $("#name").val();
		if(name.length >= 10) {
			$("#longname").show();
			$("#name").val(name.substring(0,9));
		}
		else if(name.length < 9)
			$("#longname").hide();
	});
			
});

function login() {
    $("form#login").submit();
}

function register() {
    $("form#register").submit();
}

function action() {
	if($("form#login").is(":visible"))
		login();
	else
		register();
}
