$(document).ready(function () {
	loadThreadHeader();
	loadThread();
	loadComments();
	deactivateVotes();
	$(".fancybox").fancybox({ 
		beforeShow: function () { tinymce.init({selector:'textarea',plugins:"link"}); },
		beforeClose: function () { tinymce.EditorManager.execCommand('mceRemoveControl', true, 'body'); }
	});	
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
	$("#simulator").click(function() {
		window.location = "index.php";
	});
	$("#community").click(function() {
		window.location = "community.php";
	});
});

function loadThreadHeader() {
    $.get("threadHeader.php",{id: threadID},
	function (response) {
        $("#threadheader").html(response);
    });
}

function loadThread() {
    $.get("getThread.php",{id: threadID},
	function (response) {
		thread = $.parseJSON(response);
        $("#bodytext").html(thread.body);
		$("#threadvote").html("<div class='arrow-up' onclick='vote("+threadID+","+1+")'></div><div class='numvotes'>"+thread.votes+"</div><div class='arrow-down' onclick='vote("+threadID+",-1)'></div>");
		$("#threadvote").attr("id","vote"+threadID);
	});
}

function loadComments() {
    $.get("getComments.php",{
        id: threadID
    },
	function (response) {
        comments = $.parseJSON(response);
        displayComments();
    });
}

function displayComments() {
    $("div#comments").html("<hr>");		
    for (var c_id in comments) {
        var comment = '<div class="event" id="comment' + comments[c_id].ID + '">';
		comment += '<p class="author"><img src="'+comments[c_id]["Image"]+'" />Reply by '+comments[c_id]["Name"]+'</p>';
		comment += '<p class="author">'+comments[c_id]["time"]+'</p>';
        comment += "<p>" + comments[c_id].body + "</p></div><hr />";
        $("div#comments").append(comment);
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

function submit() {
	$.post("postreply.php?v="+Math.random(),{
		id: id, 
		threadID: threadID,
		body: tinyMCE.activeEditor.getContent()
	},function(response) {
		window.location = response;
		//console.log(response);
	});
}