var wordbuffer = [];
var qid = 0;
var category = "";
var question = "";
var answer = "";
var speed = 200;
var timeoutid;
var formats = {};
var format = "";

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
	
}
function getQuestion() {	
	$("#bottom").text("");
	$.get("getQuestion.php",{
		method: "round",
		param: 1,
		set: 1,
		qid: qid,
		seed: Math.random()
	}, function(response) {
		var parts = response.split("<br>");
		category = parts[0];
		answer = $.parseJSON(parts[2]);
		qid = parts[3];
		question = parts[1];
		format = parts[4];
	});
}

function readQuestion() {
	$("#currentquestion").html(category+"<br><br>");
	$("#next").hide();
	$("#buzz").show();
	setFormat(format);
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
}

function next() {
	getQuestion();
	setTimeout("readQuestion()",1000);
	$("a#next").hide();
}

function buzz() {
	pause();
	$.fancybox.open([{
		href: "#answer",
		minWidth:"300px",
		minHeight:"100px"
	}]);
	$("#ans1").focus();
	timeoutid = setTimeout("checkAnswer()",10000);
}

function checkAnswer() {
	clearTimeout(timeoutid);
	$(".fancybox-overlay").hide();
	var ans = {};
	var i = 1;
	while($("#ans"+i).length > 0) {
		ans[i] = $("#ans"+i).val();
		$("#ans"+i).val("");
		i++;
	}
	$("body").focus();
	if(correct(ans)) {
		dumpBuffer();
		answer = answer["model"];
		$("#bottom").text(name+" was correct. The answer was "+answer+".");
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
		$("#next").show();
		$("#buzz").hide();
		
	}
	else {
		dumpBuffer();
		$("#next").show();
		$("#buzz").hide();
		$("#bottom").text("Sorry! The answer was "+answer["model"]+".");
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	}
}

function questionTimeout() {
	answer = answer["model"];
	$("#bottom").text("Time's up! The answer was "+answer+".");
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
$(document).ready(function () {
	loadFormats();
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
	
	$('#answer').keypress(function (e) {
		if (e.keyCode == 13) {
			checkAnswer();
		}
	});
});