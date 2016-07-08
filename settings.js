$(document).ready(function () {
	$("#tabs-min").tabs();
	$("#upload").click(function() {
		$("#upload_form").submit();
	});
	$("input[name=imagetype]").change(function() {
		var val = $(this).val();
		if(val == "gravatar")
			$.post("setimage.php",{val: val},function() {
				$("img.nameplate").attr("src",$("#gravatar-img").attr("src"));
			});
		else
			if(image == "gravatar")
				$.post("setimage.php",{val: "default.png"},function() {
					$("img.nameplate").attr("src","image.php?image=images/default.png&width=100&height=100");
				});
			else
				$.post("setimage.php",{val: image},function() {
					$("img.nameplate").attr("src","image.php?image=images/"+image+"&width=100&height=100");
				});
	});
	$("div#nameplate").mouseover(function(e) {
		$(this).css({"padding": "0px 20px 1px 20px", "border" :"1px solid #777777", "border-bottom":"none",
					 "background": "#EEEEEE", "background": "-moz-linear-gradient(top, #EEEEEE, #F4F4F4)",  
					 "background": "-webkit-gradient(linear, 0% 0%, 0% 100%, from(#EEEEEE), to(#F4F4F4))",  
					 "-moz-border-radius": "10px 10px 0px 0px", 
					 "-webkit-border-radius": "10px 10px 0px 0px",  
					 "border-radius": "10px 10px 0px 0px"});
		$("div#dropdown").css({"left": "-1px", "top": "auto"});
	});
	$("div#nameplate").mouseout(function(e) {
		$(this).css({"padding": "0px 20px 0px 20px", "border" :"1px solid #002232",
					 "background": "#FFFFFF", "background": "-moz-linear-gradient(top, #FFFFFF, #EEEEEE)",  
					 "background": "-webkit-gradient(linear, 0% 0%, 0% 100%, from(#FFFFFF), to(#EEEEEE))",  
					 "-moz-border-radius": "10px", 
					 "-webkit-border-radius": "10px",  
					 "border-radius": "10px"});
		$("div#dropdown").css({"left": "-999em"});
	});
	$("a#change-email").click(function() {
		$.post("changeEmail.php",{email: $("#email-input").val()}, function(message) {
			if(message == "") {
				$("#bademail").hide();
				$("img#email-check").show();
				setTimeout('$("img#email-check").hide("slow")',1000);
			}
			else {
				$("#bademail").show();
			}
		});
	});
	$("a#change-password").click(function() {
		$.post("changePassword.php",{old: $("#oldpw").val(), new1: $("#newpw1").val(), new2: $("#newpw2").val()}, function(message) {
			console.log(message);
			if(message == "invalid") {
				$("#invalid").show();
				$("#unmatching").hide();
			}
			else if(message == "unmatching") {
				$("#unmatching").show();
				$("#invalid").hide();
			}
			else {
				$("#unmatching").hide();
				$("#invalid").hide();
				$("img#password-check").show();
				setTimeout('$("img#password-check").hide("slow")',1000);
			}
		});
	});
	$("div#account").click(function(e) {
		window.location = "account.php";
	});
	$("div#logout").click(function(e) {
		window.location = "logout.php";
	});
});