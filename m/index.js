var score = 0;
var wordbuffer = [];
var qid = 0;
var category = "";
var question = "";
var answer = "";
var regex = 0;
var speed = 200;
var timeoutid;
var reading = false;
var formats = {};
var format = "";

$(document).ready(function() {
	updateFavorites();
	loadFormats();
	$("#favsearch").keyup(updateFavorites);
	$("#freeanswer").keypress(function (e) {
		if (e.keyCode == 13) {
			checkAnswer();
		}
	});
});

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
	if(favorites.length == 0 && $("#favsearch").val().length == 0)
		$("div#favqueue").html("<i>Add questions to this list by pressing the heart button next to a question.</i>");
	for(fav_id in favorites) {
		var fav = '<div data-role="collapsible" id="fav'+favorites[fav_id].ID+'">';
		fav += '<h3>'+favorites[fav_id].category + ": "+favorites[fav_id].answer+'</h3>';
        fav += "<p>" + favorites[fav_id].question +"</p>";
		fav += "<a href='javascript:void(0);' onclick='removeFavorite("+favorites[fav_id].ID+");'>Remove</a>";
        $("div#favqueue").append(fav);
	}
	$("div#favqueue").trigger("create");
}

function removeFavorite(qID) {
	$.post('unheartQuestion.php',{id: qID});
	$("div#fav"+qID).remove();
}

function getQuestion() {	
	$.get("getQuestion.php",{
		method: "random",
		param: "",
		set: "",
		qid: qid,
		seed: Math.random()
	}, function(response) {
		var parts = response.split("<br>");
		category = parts[0];
		answer = $.parseJSON(parts[2]);
		qid = parts[3];
		question = parts[1];
		format = parts[4];
		readQuestion();
	});
}

function alvaSpeakFromBuffer() {
	alvaSpeakWord(wordbuffer.shift());
}

function alvaSpeakWord(word) {
	$("#freeqbox").append(" "+word);
	$("#freeqbox")[0].scrollTop = $("#freeqbox")[0].scrollHeight;
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
}

function readQuestion() {
	setFormat(format);
	$("#freeqbox").html(category+"<br><br>");
	alvaSpeak(question, speed);
	timeoutid = setTimeout("questionTimeout()",60000*question.split(" ").length/speed+10000);
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

function nextbuzz() {
	if(reading)
		buzz();
	else
		next();
	reading = !reading;
}

function next() {
	$("#freenb .ui-btn-text").text("Buzz");
	getQuestion();
}

function buzz() {
	$("#freenb").hide();
	$("#freeanswer").popup("open");
	$("#ans1").focus();
	pause();
	timeoutid = setTimeout("checkAnswer()",10000);
}

function checkAnswer() {
	clearTimeout(timeoutid);
	$("#freeanswer").popup("close");
	var ans = {};
	var i = 1;
	while($("#ans"+i).length > 0) {
		ans[i] = $("#ans"+i).val();
		$("#ans"+i).val("");
		i++;
	}
	$("#freenb").show();	
	if(correct(ans)) {
		score++;
		dumpBuffer();
		$("#freepopupmessage").text("Correct! The answer was "+answer["model"]+".");
		$("#freepop").popup("open");
	}
	else {
		dumpBuffer();
		$("#freepopupmessage").text("'"+textFormat(ans)+"' is incorrect. The answer was "+answer["model"]+".");
		$("#freepop").popup("open");
	}
	reading = false;
	$("#freenb .ui-btn-text").text("Next");
}

function questionTimeout() {
	if(regex == 1)
		answer = answer.split("###")[0];
	$("#freepopupmessage").text("Time's up! The answer was "+answer+".");
	$("#freepop").popup("open");
	$("#freenb .ui-btn-text").text("Next");	
}

function correct(ans) {
	if(format.indexOf("ul") == 0) {
		for(id in ans) {
			match = false;
			for(id2 in answer) {
				var text = ans[id];
				if(!isNaN(answer[id2])) {
					if(isNaN(text))
						continue;
					if(+answer[id2]==+text) {
						match = true;
						answer[id2] = "a^";
						answer[id2] = "a^";
						break;
					}
				}
				var re = new RegExp(answer[id2],"gi");
				if(re.test(text)) {
					match = true;
					answer[id2] = "a^";
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
		if(!isNaN(answer[id])) {
			if(isNaN(text))
				return false;
			return +answer[id]==+text;
		}
		var re = new RegExp("^"+answer[id]+"$","gi");
		if(!re.test(text))
			return false;
	}
	return true;
}

function loadFormats() {
	$.ajax({
        type: "GET",
        url: "/formats.xml",
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
	$("div#freeanswer").html(code);
}

function textFormat(ans) {
	var args = format.split(",");
	var code = formats[args[0]];
	for(var i = 1; i < args.length; i++) {
		var re = RegExp("#"+i+"#","g");
		code = code.replace(re,args[i]);
	}
	code = code.replace(/#\d*#/g,"");	
	for(id in ans) {
		code = code.replace(new RegExp('<input.*ans?'+id+'.*?>'),ans[id]);
	}
	return code;
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