const name = prompt("What is your name?","");
const id = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
    return v.toString(16);
}); //JS UUID code from http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript/2117523#2117523
var room = new Firebase("https://vmt.firebaseio.com/room");
var active = true;

function displayChatMessage(theirname,text) {
	if(name != theirname && !active)
		document.title = theirname+' says '+text;
	var atBottom = $('#messages')[0].scrollHeight == ($('#messages')[0].scrollTop + $('#messages').outerHeight());
	$('<div/>').html(text.trim().replace(/\n/g,"<br />")).prepend($('<em/>').text(theirname + ': ')).appendTo($('#messages'));
	MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	emojify.run();
	if(atBottom)
		$('#messages')[0].scrollTop = $('#messages')[0].scrollHeight;
}

function displayProblem() {
	$("div#problem p").html(problem);
	MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	emojify.run();
}

function checkIfAdmin() {
	$(".fancybox").fancybox();
	$.ajax({url:"admins.txt",
			success: function(response) {
				admins = response.split("\n");
			},
			async: false
	});
	if($.inArray(name,admins) == -1)
		return;
	var password = prompt("Authenticate thy admin-ship.","");
	$.ajax({url:"authenticate.php",
			type: "get",
			data: {password: password},
			success: function(response) {
				if(response == "")
					alert("Authentication failed!");
				else
					$("div#problem").append(response);
			}
	});
}

$(document).ready(function () {
	checkIfAdmin();
	$('#message').keyup(function (e) {
		if (e.keyCode == 13 && !e.shiftKey) {
			var text = $('#message').val();
			if(text.trim().length == 0)
				return;
			room.child("messages").push({
				name: name,
				text: text
			});
			$('#message').val('');
		}
	});
	room.child("messages").on('child_added', function (snapshot) {
		var message = snapshot.val();
		displayChatMessage(message.name, message.text);
	});	
	room.child("messages").onDisconnect().remove();
	room.child("problem").on("value",function(snapshot) {
		problem = snapshot.val();
		displayProblem();
	});
	
	room.child("users").on("child_added",function(snapshot) {
		$("div#users").append("<div class='user' id='"+snapshot.name()+"'><p class='"+snapshot.val().status+"'>"+snapshot.val().name+"</div>");
	});
	room.child("users").on("child_changed",function(snapshot,prevName) {
		console.log(snapshot.val());
		console.log(snapshot.name());
		$("#"+snapshot.name()+" p").removeClass("online").removeClass("idle").removeClass("offline").addClass(snapshot.val().status);
	});
	room.child("users").on("child_removed",function(snapshot) {
		$("#"+snapshot.name()).remove();
	});
	room.child("users").child(id).set({
        name: name,
        status: "online"
    },function() {
		room.child("users").once("value",function(snapshot) {
			$("div#users").html("");
			for(uid in snapshot.val()) {
				$("div#users").append("<div class='user' id='"+uid+"'><p class='"+snapshot.val()[uid].status+"'>"+snapshot.val()[uid].name+"</div>");
			}
		});
	});
	room.child("users").child(id).onDisconnect().remove();
	
	emojify.setConfig({
		img_dir          	: 'emojify/images/emoji',
		emoticons_enabled	: true,
		people_enabled		: true,
		nature_enabled		: true,
		objects_enabled		: true,
		places_enabled		: true,
		symbols_enabled		: true
	});
	
	$("#messageInput").focus();
});

$(window).focus(function() {
	active = true;
	document.title = 'VMT Chat';
});
$(window).blur(function() {
	active = false;
});