var room = new Firebase("https://alva2.firebaseio.com/"+roomID);

var score = 0;
var status = "online";
var wordbuffer = [];
var qid = 0;
var category = "";
var question = "";
var answer = "";
var lastqid = 0;
var lastcategory = "";
var lastquestion = "";
var lastanswer = "";
var winning = true;
var speed = 200;
var timeoutid;
var updated = 0;
var alpha = false;
var latency = 0;
var maxLat = 0;
var active = true;
var numUsers = 0;
var formats = {};
var format = "";

function correct(ans) {
	var answer0 = answer;
	if(format.indexOf("ul") == 0) {
		for(id in ans) {
			match = false;
			for(id2 in answer0) {
				var text = ans[id];
				if(!isNaN(answer0[id2])) {
					if(isNaN(text))
						continue;
					if(+answer0[id2]==+text) {
						match = true;
						answer0[id2] = "a^";
						answer0[id2] = "a^";
						break;
					}
				}
				var re = new RegExp(answer0[id2],"gi");
				if(re.test(text)) {
					match = true;
					answer0[id2] = "a^";
					break;
				}
			}
			if(!match)
				return false;
		}
		return true;
	}
	for(id in ans) {
		var text = ans[id];
		if(!isNaN(answer0[id])) {
			if(isNaN(text))
				return false;
			return +answer0[id]==+text;
		}
		var re = new RegExp("^"+answer0[id]+"$","gi");
		if(!re.test(text))
			return false;
	}
	return true;
}

function textFormat(ans) {
	var args = format.split(",");
	var code = formats[args[0]];
	for(var i = 1; i < args.length; i++) {
		var re = RegExp("#"+i+"#","g");
		code = code.replace(re,args[i]);
	}
	code = code.replace(/#\d*#/g,"");	
	code = code.replace(/<br>/g,"; ");
	for(id in ans) {
		code = code.replace(new RegExp('<input.*ans?'+id+'.*?>'),ans[id]);
	}
	code = code.replace(/(\s|\n|\r)+;/g,"").trim();
	if(code.substr(code.length - 1) == ";")
		code = code.substring(0,code.length-1);
	return code;
}

function buzzerReset() {
	room.child("lockout").set("false");
	room.child("users").once('value',function(snapshot) {
		for(var name in snapshot.val()) {
			if(snapshot.val()[name].status == "buzzed") {
				var obj = snapshot.val()[name];
				obj.status = "online";
				room.child("users").child(name).set(obj);
			}
		}
	});
}

function setNumUsers(count) {	
	numUsers = count;
	$("span#numUsers").html(numUsers);
}

function displayChatMessage(theirname,text) {
	if(name != theirname && !active)
		document.title = theirname+' says '+text;
	text = text.replace(/([^\s-]{5})([^\s-]{5})/g,"$1&shy;$2");
	$('<div/>').html(text).prepend($('<em/>').text(theirname + ': ')).appendTo($('#messages'));
	MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	$('#messages')[0].scrollTop = $('#messages')[0].scrollHeight;
}
function alvaSpeakFromBuffer() {
	alvaSpeakWord(wordbuffer.shift());
}
function alvaSpeakWord(word) {
	$("#currentquestion").append(" "+word);
	$("#currentquestion")[0].scrollTop = $("#currentquestion")[0].scrollHeight;
	MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
}
function alvaSpeak(text,speed) {
	var words = text.split(" ");
	for(var i = 0; i < words.length; i++) {
		wordbuffer.push(words[i]);
		setTimeout("alvaSpeakFromBuffer()",60000*i/speed);
	}
}
function dumpBuffer() {
	var highestTimeoutId = setTimeout(";");
	for (var i = 0 ; i < highestTimeoutId ; i++) {
		clearTimeout(i); 
	}
	while(wordbuffer.length > 0)
		alvaSpeakFromBuffer();
	$("#next").show();
	$("#buzz").hide();	
	$.fancybox.close();
}
function getQuestion() {	
	console.log("getting");
	room.child("bottom").set("");
	$.get("getQuestion.php",{
		method: $("input:radio[name='methods']:checked").val(),
		param: $("#"+$("input:radio[name='methods']:checked").val()).val(),
		set: $("#set").val(),
		qid: qid,
		seed: Math.random()
	}, function(response) {
		var parts = response.split("<br>");
		room.child("category").set(parts[0]);
		room.child("answer").set(parts[2]);
		room.child("qid").set(parts[3]);
		room.child("question").set(parts[1]);
		room.child("format").set(parts[4]);
	});
}

function readQuestion() {
	console.log("reading");
	$("#currentquestion").html(category+"<br><br>");
	$("#next").hide();
	$("#buzz").show();
	setFormat(format);
	$("a#flag").html("<img src='images/unflagged.png' />");
	alvaSpeak(question, speed);
	timeoutid = setTimeout("questionTimeout()",60000*question.split(" ").length/speed+10000);
}

function loadFormats() {
	$.ajax({
        type: "GET",
        url: "formats.xml",
        dataType: "xml",
        success: function(xml) {
            formats = $.xml2json(xml);
        }
    });
}

function setFormat(format) {
	var args = format.split(",");
	var code = formats[args[0]];
	for(var i = 1; i < args.length; i++) {
		var re = RegExp("#"+i+"#","g");
		code = code.replace(re,args[i]);
	}
	code = code.replace(/#\d*#/g,"");
	$("div#answer").html(code);
	MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
}

function flag() {
	flag(qid);
}
function flag(qid) {
	$("a#flag").html("<img src='images/flagged.png' />");
	$.post('flagQuestion.php',{id: qid});
	room.child("mod").push("flag");
	room.child("bottom").set("The question was flagged by "+name+".");
}

function heart() {
	$.post('heartQuestion.php',{id: qid});
}

function next() {
	room.child("mod").push("read");
}

function buzz() {
	setStatus("buzzed");
	room.child("lockout").set("true");
	room.child("mod").push("pause");
	$.fancybox.open([{
		href: "#answer",
		minWidth:"300px",
		minHeight:"100px"
	}]);
	$("#ans1").focus();
	timeoutid = setTimeout("checkAnswer()",10000);
}

function checkAnswer() {
	console.log("checking");
	clearTimeout(timeoutid);
	room.child("lockout").set("false");
	setStatus("online");
	var ans = {};
	var i = 1;
	while($("#ans"+i).length > 0) {
		ans[i] = $("#ans"+i).val();
		$("#ans"+i).val("");
		i++;
	}
	$("body").focus();
	if(correct(ans)) {
		score++;
		room.child("users").child(name).set({score: score, status: status, image: image});
		room.child("users").child(name).setPriority(score);
		room.child("mod").push("dump");
		room.child("bottom").set(name+" was correct. The answer was "+answer["model"]+".");
		$("#next").show();
		$("#buzz").hide();
		
	}
	else {
		room.child("bottom").set(name+" said "+textFormat(ans)+", and was incorrect.");
		$.fancybox.close();
		room.child("mod").push("resume");
	}
}

function questionTimeout() {
	room.child("bottom").set("Time's up! The answer was "+answer["model"]+".");
	$("#next").show();
	$("#buzz").hide();	
}
	
function pause() {			
	var highestTimeoutId = setTimeout(";");
	for (var i = 0 ; i < highestTimeoutId ; i++) {
		clearTimeout(i); 
	}
}
function resume() {
	if(wordbuffer.length > 0)
		for(var i = 0; i < wordbuffer.length; i++)
			setTimeout("alvaSpeakFromBuffer()",60000*i/speed);
	timeoutid = setTimeout("questionTimeout()",60000*wordbuffer.length/speed+10000);
}			
function setStatus(s) {
	status = s;
	room.child("users").child(name).set({ score: score, status: status, image: image	});
}
function logDataUpdate() {
	updated++;
	if(updated == 5) {
		updated = 0;
		if(lastcategory.length > 0) {
			$("<div/>").html(lastcategory+"<br />"+lastquestion+"<br />"+"Answer: "+lastanswer["model"]).prependTo($("#questionhistory"));
			$("<h3/>").html(lastcategory).prependTo($("#questionhistory"));
			$("#questionhistory").accordion("refresh");
		}
	}
}
function findLatency() {
	room.child("sync_"+name).set(new Date().getTime(), function() {
		room.child("sync_"+name).once("value", function(snapshot) {
			latency = new Date().getTime()-snapshot.val();
			if(latency > maxLat)
				room.child("maxlat").set(latency);
		});
	});
}

function help() {
	$.fancybox.open([{
		href: "#help"
	}]);
}
$(document).ready(function () {
	setInterval("findLatency()",1000);
	$(".fancybox").fancybox();
	$("#questionhistory").accordion({ collapsible: true });
	$("#tabs-min").tabs();
	loadFormats();
	$('#message').keypress(function (e) {
		if (e.keyCode == 13) {
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
	$("div#account").click(function(e) {
		window.location = "account.php";
	});
	$("div#logout").click(function(e) {
		window.location = "logout.php";
	});
	room.child("messages").on('child_added', function (snapshot) {
		var message = snapshot.val();
		displayChatMessage(message.name, message.text);
	});	
	room.child("lockout").on('value', function (snapshot) {
		lockout = snapshot.val();			
		if(lockout == "true")
			$("#buzz").hide();
		if(lockout == "false" && !$("#next").is(":visible"))
			$("#buzz").show();			
	});
	room.child("users").on('value', function (snapshot) {
		$("div#userpics").html("");
		winning = true;
		alphatemp = true;
		var count = 0;
		for(var theirname in snapshot.val()) {
			count++;
			if(snapshot.val()[theirname].score > score)
				winning = false;
			if(theirname < name)
				alphatemp = false;
			$("div#userpics").append("<div class='user"+snapshot.val()[theirname].status+"'><img src='"+snapshot.val()[theirname].image+"' />"+theirname+" ("+snapshot.val()[theirname].score+")</div>");
		}
		alpha = alphatemp;
		setNumUsers(count);
	});
	room.child("mod").on('child_added', function (snapshot) {
		if(snapshot.val() == "read") {
			if(alpha)
				getQuestion();
			setTimeout("readQuestion()",maxLat-latency+1000);
			$("a#next").hide();
		}
		else if(snapshot.val() == "resume")
			resume();
		else if(snapshot.val() == "pause")
			pause();
		else if(snapshot.val() == "dump")
			dumpBuffer();
		else if(snapshot.val() == "flag") {
			dumpBuffer();
			$("a#flag").html("<img src='images/flagged.png' />");
		}
	});	
	room.child("category").on("value",function(snapshot) {
		lastcategory = category;
		category = snapshot.val();
		logDataUpdate();
	});
	room.child("answer").on("value",function(snapshot) {
		lastanswer = answer;
		console.log(snapshot.val());
		answer = jQuery.parseJSON(snapshot.val());
		logDataUpdate();
	});
	room.child("qid").on("value",function(snapshot) {
		lastqid = qid;
		qid = snapshot.val();
		logDataUpdate();
	});
	room.child("question").on("value",function(snapshot) {
		console.log(snapshot.val());
		lastquestion = question;
		question = snapshot.val();
		logDataUpdate();
	});
	room.child("format").on("value",function(snapshot) {
		format = snapshot.val();
		logDataUpdate();
	});
	room.child("bottom").on("value",function(snapshot) {
		$("#bottom").html(snapshot.val());
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	});
	room.child("maxlat").on("value", function(snapshot) {
		maxLat = snapshot.val();
	});
	
	room.child("messages").onDisconnect().remove();
	room.child("mod").onDisconnect().remove();
	room.child("users").child(name).onDisconnect().remove();
	room.child("maxlat").onDisconnect().set(500);
	
	new Firebase("https://alva2.firebaseio.com/users/").child(id).set({
        name: name,
        room: roomID,
        roomName: roomName,
        image: image,
        status: "online"
    });
	
	new Firebase("https://alva2.firebaseio.com/users/").child(id).onDisconnect().remove();
	
	setStatus("online");
	
	$("#messageInput").focus();
	$('#answer').keypress(function (e) {
		if (e.keyCode == 13) {
			checkAnswer();
		}
	});
});

document.onIdle = function () {
	setStatus("idle");
}
document.onAway = function () {
	setStatus("offline");
}
document.onBack = function (isIdle, isAway) {
	setStatus("online");
}

$(window).focus(function() {
	active = true;
	document.title = 'Alva - '+roomName;
});
$(window).blur(function() {
	active = false;
});


setIdleTimeout(60000);
setAwayTimeout(300000);