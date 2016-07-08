var allUsers = {};
var friends = {};
var rooms = {};
var requests = {};
var favorites = {};
var users = {};
var expanded = 0;

$(document).ready(function () {
	getFriends();
	updateFriends();
    setInterval("updateFriends()", 5000);
	updateRequests();
    setInterval("updateRequests()", 5000);
	$("#myroomsearch").keyup(updateRooms("my"));
	$("#favroomsearch").keyup(updateRooms("fav"));
	$("#otherroomsearch").keyup(updateRooms("other"));
	$("#usersearch").keyup(updateUsers);
	$("#favsearch").keyup(updateFavorites);
	updateRooms();
	updateFavorites();
	$(".fancybox").fancybox();	
    $(document).tooltip();
	$("#tabs-ultramin").tabs();
	$("div#account").click(function(e) {
		window.location = "account.php";
	});
	$("div#logout").click(function(e) {
		window.location = "logout.php";
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
	$("#roomname").keyup(function() {
		roomname = $("#roomname").val();
		if(roomname.length >= 10) {
			$("#longname").show();
			$("#roomname").val(roomname.substring(0,9));
		}
		else if(roomname.length < 9)
			$("#longname").hide();
	});
	$("#trainer").click(function() {
		window.location = "train.php";
	});
	$("#community").click(function() {
		window.location = "community.php";
	});
});

function getAllUsers() {
    var users = $.Deferred();
    var user_ref = new Firebase("https://alva2.firebaseio.com/users/");
    user_ref.child(id).set({
        name: name,
        room: 1,
        roomName: "dashboard",
        image: image,
        status: "online"
    }, function () {
        var self_ref = user_ref.child(id);
        user_ref.once("value", function (snapshot) {
            users.resolve(snapshot.val());
        });
    });
	user_ref.child(id).onDisconnect().remove();
    return users.promise();
}

function updateFriends() {
    getAllUsers().then(function (users0) {
		console.log(allUsers);
		console.log(users0);
		allUsers = users0;
		displayFriends();
    });
}

function getFriends() {
    $.get("findFriends.php", {
        id: id,
        query: $("input#friendsearch").val(),
		async: false
    }, function (response) {
        friends = $.parseJSON(response);
    });
}

function displayFriends() {
    $("div#friendqueue").html("");
	console.log("display");
    for (var uid in allUsers) {
		if(allUsers[uid].name == name)
			continue;
		for(var friend_id in friends) {
			if(allUsers[uid].name == friends[friend_id].Name) {						
				var friend = '<div class="event" id="' + uid + '">';
				friend += "<img src='" + allUsers[uid].image + "' class='event' />";
				friend += "<h2>" + allUsers[uid].name + "</h2>";
				friend += "Currently in " + allUsers[uid].roomName + "</div>";
				$("div#friendqueue").append(friend);
			}
		}
    }
    main: for (var friend_id in friends) {
		for(var uid in allUsers)
			if(allUsers[uid].name == friends[friend_id].Name)
				continue main;
			else
				console.log(allUsers[uid].name +" "+ friends[friend_id].Name);
        var friend = '<div class="event" id="' + friend_id + '">';
        friend += "<img src='" + friends[friend_id].Image + "' class='event' />";
        friend += "<h2 class='light'>" + friends[friend_id].Name + "</h2>";
        $("div#friendqueue").append(friend);
    }
}

function updateRooms(list) {
    $.get("findRooms.php", {
        id: id,
        query: $("input#"+list+"roomsearch").val()
    }, function (response) {
        rooms = $.parseJSON(response);
        displayRooms();
    });
}

function displayRooms() {
    $("div#myroomqueue").html("");
    $("div#otherroomqueue").html("");
    $("div#favroomqueue").html("");
	if(!!rooms["myrooms"])
		$("div#myroomqueue").html("<hr />");	
	if(!!rooms["otherrooms"])
		$("div#otherroomqueue").html("<hr />");		
	if(!!rooms["favrooms"])
		$("div#favroomqueue").html("<hr />");		
    for (var room_id in rooms["myrooms"]) {
        var room = '<div class="event" id="room' + rooms["myrooms"][room_id].ID + '">';
        room += "<h2>" + rooms["myrooms"][room_id].Name + "</h2>";
        room += "<p>" + rooms["myrooms"][room_id].Description + "</p>";
        room += "<a href='#' onclick='joinRoom(" + rooms["myrooms"][room_id].ID + ")'>Enter</a></div><hr />";
        $("div#myroomqueue").append(room);
    }
    for (var room_id in rooms["favrooms"]) {
        var room = '<div class="event" id="room' + rooms["favrooms"][room_id].ID + '">';
        room += "<h2>" + rooms["favrooms"][room_id].Name + "</h2>";
        room += "<p>" + rooms["favrooms"][room_id].Description + "</p>";
		if(rooms["favrooms"][room_id].Password.length > 0) {
			room += "<span class='error hidden' id='badroompass"+rooms["favrooms"][room_id].ID+"'>Incorrect password.</span>";
			room += "<input type='password' id='roompass"+rooms["favrooms"][room_id].ID+"' />";
		}
        room += "<a href='javascript:void(0);' onclick='joinRoom(" + rooms["favrooms"][room_id].ID + ")'>Enter</a></div><hr />";
        $("div#favroomqueue").append(room);
		$("a#joinRoom"+rooms["favrooms"][room_id].ID).click(function(e) {
			e.preventDefault();
			joinRoom(rooms["favrooms"][room_id].ID);
		});
    }
    for (var room_id in rooms["otherrooms"]) {
        var room = '<div class="event" id="room' + rooms["otherrooms"][room_id].ID + '">';
        room += "<a href='javascript:void(0);' onclick='heart(" + rooms["otherrooms"][room_id].ID + ")' id='roomheart'><img src='images/heart.png'></a>";
        room += "<h2>" + rooms["otherrooms"][room_id].Name + "</h2>";
        room += "<p>" + rooms["otherrooms"][room_id].Description + "</p>";
		if(rooms["otherrooms"][room_id].Password.length > 0) {
			room += "<span class='error hidden' id='badroompass"+rooms["otherrooms"][room_id].ID+"'>Incorrect password.</span>";
			room += "<input type='password' id='roompass"+rooms["otherrooms"][room_id].ID+"' />";
		}
        room += "<a href='javascript:void(0);' onclick='joinRoom(" + rooms["otherrooms"][room_id].ID + ")'>Enter</a></div><hr />";
        $("div#otherroomqueue").append(room);
		$("a#joinRoom"+rooms["otherrooms"][room_id].ID).click(function(e) {
			e.preventDefault();
			joinRoom(rooms["otherrooms"][room_id].ID);
		});
    }
}

function heart(room_id) {
	$.post("heartRoom.php",{id: room_id},function() {
		$("#room"+room_id).remove();
		updateRooms("fav");
		$("#tabs-ultramin").tabs({ active: 1 });
	});
}

function updateUsers() {
	$.get("findUsers.php", {
        id: id,
        query: $("input#usersearch").val()
    }, function (response) {
        users = $.parseJSON(response);
        displayUsers();
    });
}

function displayUsers() {
    $("div#userqueue").html("");
    for (var user_id in users) {
        var user = '<div class="event" id="user' + user_id + '">';
        user += "<img src='images/" + users[user_id].Image + "' class='event' />";
        user += "<h2>" + users[user_id].Name + "</h2>";
        user += "<a onclick='requestFriendship("+user_id+")' class='button'>";
		if(users[user_id].status == "friended") {
			user += "Friendship Pending</a>";
			user += "<textarea disabled='disabled' class='message' rows='2' cols='25' id='message"+user_id+"' placeholder='"+users[user_id].message+"' /></div>";
		} 
		else {
			user += "Add as Friend</a>";
			user += "<textarea class='message' rows='2' cols='25' id='message"+user_id+"' placeholder='Message to be attached to friendship request...' /></div>";
		}
        $("div#userqueue").append(user);
    }
}

function requestFriendship(recipientID) {
	$.get("requestFriend.php", {
		askerID: id,
		recipientID: recipientID,
		message: $("div#user"+recipientID+" textarea").val()
	}, function(response) {
		$("div#user"+recipientID+" a").html("Friendship Pending").click(function(e) {
			e.preventDefault();
		});
		$("textarea#message"+recipientID).attr("disabled","disabled"); ;
	});
}

function updateRequests() {
	$.get("getRequests.php", {
        id: id,
    }, function (response) {
        requests = $.parseJSON(response);
        displayRequests();
    });
}

function displayRequests() {
    $("div#requests").html("");
    for (var user_id in requests) {
        var user = '<div class="request" id="request' + user_id + '">';
        user += "<p>" + requests[user_id].name + " wants to be friends. ";
		if(requests[user_id].message.length != 0) {
			user += "<a href='#message" + user_id + "' class='fancybox'>View Message</a></p>";
		}
		user += "<a href = '#' onclick='acceptFriend("+user_id+")'>Accept</a> | <a href = '#' onclick='declineFriend("+user_id+")'>Decline</a></div>";
		user += "<div style='display:none;'><div id='message"+user_id+"'>"+requests[user_id].message+"</div></div>";

        $("div#requests").append(user);
    }
	$(".fancybox").fancybox();
}

function acceptFriend(friendID) {
	$.get("acceptFriend.php", {
        id: id,
		friendID: friendID
    }, function (response) {
        $("div#request"+friendID).hide("slow");
		getFriends();
    });
}

function declineFriend(friendID) {
	$.get("declineFriend.php", {
        id: id,
		friendID: friendID
    }, function (response) {
        $("div#request"+friendID).hide("slow");
    });
}

function updateFavorites() {
	$.get("getFavorites.php", {
        id: id,
        query: $("input#favsearch").val()
    }, function (response) {
        favorites = $.parseJSON(response);
        displayFavorites();
    });
}

function displayFavorites() {
	$("div#favqueue").html("");
	if(favorites.length == 0)
		$("div#favqueue").html("<i>Add questions to this list by pressing the save button inside a room.</i>");
	for(fav_id in favorites) {
		var fav = '<div class="event fav" id="fav' + favorites[fav_id].ID + '">';
        fav += "<p class='favlabel' id='favlabel" + favorites[fav_id].ID + "' onclick='expand("+favorites[fav_id].ID+")'>" + favorites[fav_id].category + ": "+$.parseJSON(favorites[fav_id].answer)["model"]+"</p>";
        $("div#favqueue").append(fav);
	}
}

function expand(qid) {
	$("div#fav"+expanded+" p:last").remove();
	$("div#fav"+expanded+" a:last").remove();
	if(qid != expanded) {
		expanded = qid;
		$("div#fav"+expanded).append("<p>"+favorites[expanded].question+"</p>");
		$("div#fav"+expanded).append("<a href='javascript:void(0);' onclick='removeExpanded()'>Remove</a>");
	}
	else
		expanded = 0;
}

function removeExpanded() {
	$.post('unheartQuestion.php',{id: expanded});
	$("div#fav"+expanded).remove();
	expanded = 0;
}

function createRoom() {
	$.post("createRoom.php", {
		id: id,
		roomname: $("input#roomname").val(),
		roomdesc: $("input#roomdesc").val(),
		roompriv: $("input#roompriv").is(":checked") ? "1" : "0",
		roompass: $("input#roompass").val()
	}, function(response) {
		if(response == "bad name")
			$("span#badname").show();
		else {
			$("span#badname").hide();
			$.fancybox.close();
			updateRooms();
			var room = new Firebase("https://alva2.firebaseio.com/"+response);
			room.child("messages").push({name: "Alva", message: "Your room has been successfully created."});
			room.child("lockout").set("false");
			room.child("mod").set("");
		}
	});
}		

function joinRoom(roomID) {
	var pass = "";
	if($("input#roompass"+roomID).length != 0)
		pass = $("input#roompass"+roomID).val();
	$.post("joinRoom.php",{
		id: id,
		roomID: roomID,
		pass: pass
	}, function(response) {
		if(response == "badpass") {
			$("span#badroompass"+roomID).show();
		}
		else {
			window.location = "alva.php?room="+roomID;
		}
	});
}