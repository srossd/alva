$(document).ready(function () {
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
	$("#community").click(function() {
		window.location = "community.php";
	});
	$("#simulator").click(function() {
		window.location = "index.php";
	});
	$("#subjects").tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $("#subjects li").removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
	$("#geo-terms .progress").css("width","40%");
	$(".trainingset").mouseover(function(e) {
		$(this).css("background","#CCCCEE");
	});
	$(".trainingset").mouseout(function(e) {
		$(this).css("background","#FFFFFF");
	});
	$(".fancybox").fancybox();
	loadRanking();
	getFriends();
	loadChallenges();
});

function loadRanking() {
	$.get("getRanking.php",function(response) {
		var ranking = $.parseJSON(response);
		displayRanking(ranking);
	});
}

function displayRanking(ranking) {
	for(var i = 0; i < ranking.length && i < 10; i++) {
		var row = ranking[i];
		var rank = "<tr class='"+(i % 2 == 0 ? "even" : "odd")+"'>";
		rank += "<td>"+(i+1)+"</td>";
		rank += "<td><img width='50' src='"+row.image+"'></td>";
		rank += "<td>"+row.name+"</td>";
		rank += "<td>"+row.score+"</td></tr>";
		$("#topranks table").append(rank);
	}
}

function getFriends() {
    $.get("findFriends.php", {
        id: id,
        query: ""
    }, function (response) {
        friends = $.parseJSON(response);
    });
}

function practice(setID) {
	window.location = "trainingset.php?id="+setID;
}

function challenge(setID) {
	$("h3#title").text("Challenge in "+$("div#"+setID+" p").text());
	$("div#userselect").html("<table>");
	for(var i = 0; i < friends.length; i++) {
		var radio = "<tr><td><input type='radio' name='user' value='"+friends[i].ID+"'></td><td>"+friends[i].Name+"</td></tr>";
		$("div#userselect").append(radio);
	}
	$("div#userselect td").css("padding","4px");
	$("div#userselect").css("margin","0 100");
	$("input#setID").val(setID);
}

function submitChallenge() {
	var setID = $("input#setID").val();
	var userID = $('input[name="user"]:checked').val();
	$.post("makeChallenge.php",{setID: setID, user1: id, user2: userID},
	function(response) {
		$.fancybox.close();
		playChallenge(response);
	});
}

function loadChallenges() {
	$.get("getChallenges.php",{id: id},
	function(response) {
		var challenges = $.parseJSON(response);
		for(var i = 0; i < challenges.length; i++) {
			var row = "<tr class='"+challenges[i].type+"'><td><b>"+challenges[i].name+"</b></td><td>"+challenges[i].setName+"</td><td><span class='score'>"+challenges[i].score+"</span></td></tr>";
			$("#challenges table").append(row);
		}
	});
}

function playChallenge(ID) {
	window.location = "challenge.php?id="+ID;
}