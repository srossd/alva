votes = {};
voted = [];

$(document).ready(function () {
	$("#mysearch").keyup(updateMyPosts);
	$("#threadsearch").keyup(updateThreads);
	$("#recentsearch").keyup(updateRecentPosts);
	updateMyPosts();
	updateThreads();
	updateRecentPosts();
	deactivateVotes();
	$(".fancybox").fancybox();	
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
	$("#trainer").click(function() {
		window.location = "train.php";
	});
});

function updateThreads() {
    $.get("findThreads.php",{
		forumID: forumID,
        query: $("input#threadsearch").val()
    },
	function (response) {
        posts = $.parseJSON(response);
        displayThreads();
    });
}

function displayThreads() {
    $("div#threadqueue").html("<hr>");		
    for (var post_id in posts) {
        var post = '<div class="event votecontainer" id="post' + posts[post_id].ID + '"><div class="vote" id="vote'+posts[post_id].ID+'">';
		post += "<div class='arrow-up' onclick='vote("+posts[post_id].ID+","+1+")'></div><div class='numvotes'>"+posts[post_id].votes+"</div><div class='arrow-down' onclick='vote("+posts[post_id].ID+",-1)'></div></div><div class='voteon'>";
        post += "<h2>" + posts[post_id].subject + "</h2>";
        post += "<p class='author'>by " + posts[post_id].Name + " on "+posts[post_id].time+"</p>";
        post += "<p>" + trim(posts[post_id].body) + "</p>";
        post += "<a href='thread.php?id="+posts[post_id].ID+"'>Read</a></div></div><hr />";
        $("div#threadqueue").append(post);
    }
}

function updateMyPosts() {
    $.get("findMyPosts.php",{
        id: id,
		forumID: forumID,
        query: $("input#mysearch").val()
    },
	function (response) {
		console.log("hi");
        myposts = $.parseJSON(response);
        displayMyPosts();
    });
}

function displayMyPosts() {
    $("div#myqueue").html("<hr>");		
    for (var post_id in myposts) {
        var post = '<div class="event votecontainer" id="post' + myposts[post_id].ID + '"><div class="vote" id="vote'+myposts[post_id].ID+'">';
		post += "<div class='arrow-up' onclick='vote("+myposts[post_id].ID+","+1+")'></div><div class='numvotes'>"+myposts[post_id].votes+"</div><div class='arrow-down' onclick='vote("+myposts[post_id].ID+",-1)'></div></div><div class='voteon'>";
        post += "<h2>" + myposts[post_id].subject + "</h2>";
        post += "<p class='author'>by " + myposts[post_id].Name + " on "+myposts[post_id].time+"</p>";
        post += "<p>" + trim(myposts[post_id].body) + "</p>";
        post += "<a href='thread.php?id="+myposts[post_id].ID+"'>Read</a></div></div><hr />";
        $("div#myqueue").append(post);
    }
}

function updateRecentPosts() {
    $.get("findRecentPosts.php",{
		forumID: forumID,
        query: $("input#recentsearch").val()
    },
	function (response) {
        recentposts = $.parseJSON(response);
        displayRecentPosts();
    });
}

function displayRecentPosts() {
    $("div#recentqueue").html("<hr>");		
    for (var post_id in recentposts) {
        var post = '<div class="event votecontainer" id="post' + recentposts[post_id].ID + '"><div class="vote" id="vote'+recentposts[post_id].ID+'">';
		post += "<div class='arrow-up' onclick='vote("+recentposts[post_id].ID+","+1+")'></div><div class='numvotes'>"+recentposts[post_id].votes+"</div><div class='arrow-down' onclick='vote("+recentposts[post_id].ID+",-1)'></div></div><div class='voteon'>";
        post += "<h2>" + recentposts[post_id].subject + "</h2>";
        post += "<p class='author'>by " + recentposts[post_id].Name + " on "+recentposts[post_id].time+"</p>";
        post += "<p>" + trim(recentposts[post_id].body) + "</p>";
        post += "<a href='thread.php?id="+recentposts[post_id].ID+"'>Read</a></div></div><hr />";
        $("div#recentqueue").append(post);
    }
}

function deactivateVotes() {
	$.get("threads_voted.php",{id: id},
	function(response) {
		voted = $.parseJSON(response);
		for(var i = 0; i < voted.length; i++) {
			var threadID = voted[i];
			$("#vote"+threadID+" .arrow-up").hide();
			$("#vote"+threadID+" .arrow-down").hide();
			$("#vote"+threadID+" .numvotes").css("margin-top","15px");
		}
	});
}

function vote(threadID, sign) {
	$.post("vote_thread.php",{id: id, threadID: threadID, vote: sign},
	function(response) {
		$("#vote"+threadID+" .arrow-up").hide();
		$("#vote"+threadID+" .arrow-down").hide();
		$("#vote"+threadID+" .numvotes").html(parseInt($("#vote"+threadID+" .numvotes").html())+sign);
		$("#vote"+threadID+" .numvotes").css("margin-top","15px");
	});
}

function trim(str) {
	return (str.lastIndexOf(" ") > 100 ? str.substring(0,str.substring(100).indexOf(" ")+100)+"..." : str);
}