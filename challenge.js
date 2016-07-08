var set = {};
var data = {};
var answer = "";
var anstype = "";
var epsilon = 0;
var refract = false;
var chemparser;
var mathparser;
var time = 90;
var logged = true;
var questionID = 0;

function loadChallenge(cID) {
	$.ajax({
        type: "GET",
        url: "getChallenge.php",
		data: {cID: cID},
        async: false,
        success: function(response) {
			challenge = $.parseJSON(response);
		}
    });
	challenge.score1 = 0;
	$("#score1").text(challenge.score1);
	$("#score2").text(challenge.score2 == -1 ? "?" : challenge.score2);
	$("#clock").text(timeFormat(time));
	setTimeout(stepTime,1000);
	loadTrainingSet(challenge.setFile);
}

function stepTime() {
	if(time == 1) {
		endGame();
		return;
	}
	time--;
	$("#clock").text(timeFormat(time));
	setTimeout(stepTime,1000);
}

function timeFormat(time) {
	return Math.floor(time/60)+":"+((time >= 60 && time < 70) || (time < 10) ? 0 : "") + (time % 60);
}

function loadTrainingSet(url) {
    $.ajax({
        type: "GET",
        url: url,
        dataType: "xml",
        success: function(xml) {
            set = $.xml2json(xml);
			loadLoggedQuestions();
        }
    });
}   

function loadLoggedQuestions() {
	$.get("loadLoggedQuestions.php",{cID: challengeID},
	function(response) {
		if(response.length == 0) {
			logged = false;
			loggedQuestions = {};
		}
		else
			loggedQuestions = $.parseJSON(response);
		loadData();
	});
}

function loadData() {
	if(!!(set.data)) {
		if(typeof set.data == 'string') {
			var file = set.data;
			$.getJSON(file,function(json) {
				data[file.substring(5,file.length-5)] = json;
			}).done(loadQuestion);
		}
		else {
			for(data_id in set.data) {
				var file = set.data[data_id];
				$.getJSON(file,function(json) {
					data[file.substring(5,file.length-5)] = json;
				}).done(loadQuestion);
			}
		}
	}
	else
		loadQuestion();
}

function loadQuestion() {
	if(!logged || questionID >= loggedQuestions.length) {
		$("#trainanswer").val("").focus();

		var problem = set.problem;
		if(!!(set.problem.length))
			var problem = randSelect(set.problem);
		
		if(!!(problem.code))
			eval(problem.code);
		
		var vars = {};
		if(!!problem.variable && !!(problem.variable.length))
			for(var var_id in problem.variable) {
				var variable = problem.variable[var_id];
				if(!!(variable.lower)) {
					variable.lower = parseInt(variable.lower);
					variable.upper = parseInt(variable.upper);
					variable.step = parseInt(variable.step);
					vars[variable.id] = variable.lower + variable.step*Math.floor(Math.random()*(variable.upper-variable.lower)/variable.step);
				}
				else {
					vars[variable.id] = variable.obj+"[\""+randSelect(Object.keys(eval(variable.obj)))+"\"]";
				}
			}
		else if(!!(problem.variable)) {
			var variable = problem.variable;
			if(!!(variable.lower)) {
				variable.lower = parseInt(variable.lower);
				variable.upper = parseInt(variable.upper);
				variable.step = parseInt(variable.step);
				vars[variable.id] = variable.lower + variable.step*Math.floor(Math.random()*(variable.upper-variable.lower)/variable.step);
			}
			else {
				vars[variable.id] = variable.obj+"[\""+randSelect(Object.keys(eval(variable.obj)))+"\"]";
			}
		}
		
		var exprs = {};
		if(!!(problem.expression.length))
			for(var expr_id in problem.expression)
				exprs[problem.expression[expr_id].id] = evaluate(problem.expression[expr_id].text,vars);
		else {
			var expression = problem.expression;
			exprs[expression.id] = evaluate(expression.text,vars);
		}
				
		var variation = randSelect(problem.variation);
		
		var statement = variation.statement;
		for(var expr_id in exprs)
			statement = statement.replace(new RegExp("#"+expr_id+"#","gi"),exprs[expr_id]);
		statement = chemFormat(statement);
		$("#question").html(statement);
			
		var ans = variation.answer;
		answer = ans.value;
		anstype = ans.type;
		setupAnswer();
		for(var expr_id in exprs)
			answer = answer.replace(new RegExp("#"+expr_id+"#","gi"),exprs[expr_id]);
		
		if(!isNaN(answer)) {
			answer = parseFloat(answer);
			if(!(!!ans.value.accuracy) || ans.value.accuracy == "exact")
				epsilon = 0;
			else
				epsilon = Math.pow(10,parseInt(ans.value.accuracy));
		}
		if(!!(ans.trailer))
			$("#trailer").html(ans.trailer);
		else
			$("#trailer").html("");
		
		$("#message").text("");
		$("#solution").hide();
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
		$.post("logChallengeQuestion.php",{	
			cID: challengeID, 
			question: statement, 
			answer: answer, 
			anstype: anstype, 
			trailer: (!!(ans.trailer) ? ans.trailer : ""), 
			accuracy: (!!(ans.accuracy) ? ans.accuracy : "")
		});
	}
	else {
		var q = loggedQuestions[questionID];
		$("#question").html(q.question);
		answer = q.answer;
		anstype = q.anstype;
		var accuracy = q.accuracy;
		if(accuracy == "" || ans.value.accuracy == "exact")
				epsilon = 0;
			else
				epsilon = Math.pow(10,parseInt(accuracy));
		$("#trailer").html(q.trailer);
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	}
	questionID++;
}

function chemFormat(text) {
	text = text.replace(/chem\[(.*?)\]/gi, function(a,b) { return chemparser.parse(b).toHTML(); });
	return text;
}

function setupAnswer() {
	if(anstype == "chem") {
		$("#preview").text("").show();
		preview = "chem";
	}
	else if(anstype == "math") {
		$("#preview").text("").show();
		preview = "math";
	}	
	else {
		$("#preview").hide();
		preview = "";
	}
}

function checkAnswer() {
	if(anstype == "chem")
		if(chemparser.parse(answer).equals(chemparser.parse($("#trainanswer").val())))
			correct();
		else
			wrong();
	else if(anstype == "math")
		if(mathparser.parse(answer).equals(mathparser.parse($("#trainanswer").val())))
			correct();
		else
			wrong();
	else if(!isNaN($("#trainanswer").val())) {
		var input = parseFloat($("#trainanswer").val());
		if(Math.abs(input-answer) <= epsilon)
			correct();
		else
			wrong();
	}
	else {
		var input = $("#trainanswer").val();
		var re = RegExp("^"+preg_quote(answer)+"$","gi");
		if(re.test(input))
			correct();
		else
			wrong();
	}
	$("#trainanswer").val("");
	$("#trainanswer").focus();
	
}

function correct() {
	incrementScore();
	loadQuestion();
}

function wrong() {
	decrementScore();
}

function incrementScore() {
	challenge.score1 = parseInt(challenge.score1)+1;
	$("#score1").text(challenge.score1);
}
function decrementScore() {
	challenge.score1 = parseInt(challenge.score1)-1;
	$("#score1").text(challenge.score1);
}

function endGame() {
	$.post("setScore.php",{cID: challengeID, id: id, score: parseInt(challenge.score1)},
	function(response) {
		console.log(response);
		if(challenge.score2 == -1) {
			$("#message").html("You scored <b>"+challenge.score1+"</b> against "+challenge.user2name+" in "+challenge.setName+". <a href='train.php'>Keep on practicing</a> while you wait for your opponent to complete the challenge.");
		}
		else if(challenge.score1 > challenge.score2) {
			$("#message").html("Congratulations! You won with a score of <b>"+challenge.score1+"</b> in "+challenge.setName+". Your new ranking for this training set is <b>"+response+"</b>.");
		}
		else if(challenge.score1 < challenge.score2) {
			$("#message").html("Sorry! You scored <b>"+challenge.score1+"</b> in "+challenge.setName+", but "+challenge.user2name+" won with a score of "+challenge.score2+". Your new ranking for this training set is <b>"+response+"</b>.");
		}
		else {
			$("#message").html("It's a draw! You and "+challenge.user2name+" both scored <b>"+challenge.score1+"</b> in "+challenge.setName+". Your new ranking for this training set is <b>"+response+"</b>.");
		}
		$.fancybox.open([{
			href: "#message",
			minWidth: "300px",
			minHeight: "100px"
		}]);
	});
		
}
	
function randSelect(obj) {
	if(!!(obj.length) && !!(obj[0])) {
		var r = Math.floor(Math.random()*obj.length);
		return obj[r];
	}
	else if(!!(Object.keys(obj).length)) {
		var keys = Object.keys(obj);
		return obj[randSelect(keys)];
	}
	else
		return obj;
}

function evaluate(expression,vars) {
	names = Object.keys(vars);
	names.sort(function(a, b){
	  return b.length - a.length;
	});
	for(var i in names) {
		var regex = new RegExp(names[i],"g");
		expression = expression.replace(regex,vars[names[i]].toString());
	}
	console.log(expression);
	return eval(expression);
}

function preg_quote (str, delimiter) {
  // http://kevin.vanzonneveld.net
  // +   original by: booeyOH
  // +   improved by: Ates Goral (http://magnetiq.com)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Onno Marsman
  // +   improved by: Brett Zamir (http://brett-zamir.me)
  // *     example 1: preg_quote("$40");
  // *     returns 1: '\$40'
  // *     example 2: preg_quote("*RRRING* Hello?");
  // *     returns 2: '\*RRRING\* Hello\?'
  // *     example 3: preg_quote("\\.+*?[^]$(){}=!<>|:");
  // *     returns 3: '\\\.\+\*\?\[\^\]\$\(\)\{\}\=\!\<\>\|\:'
  return (str + '').replace(new RegExp('[.\\\\+*?\\[\\^\\]$(){}=!<>|:\\' + (delimiter || '') + '-]', 'g'), '\\$&');
}

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
	$("#trainer").click(function() {
		window.location = "train.php";
	});
	$("#subjects").tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $("#subjects li").removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
	$(".trainingset").mouseover(function(e) {
		$(this).css("background","#CCCCEE");
	});
	$(".trainingset").mouseout(function(e) {
		$(this).css("background","#FFFFFF");
	});
	
	$.ajax({url: "chemgrammar.peg",dataType: "text",success: function(msg) {
		chemparser = PEG.buildParser(msg);
	}});
	$.ajax({url: "mathgrammar.peg",dataType: "text",success: function(msg) {
		mathparser = PEG.buildParser(msg);
	}});
	
	$("#trainanswer").keyup(function() {
		$("#preview").text("");
		if(preview == "math") {
			$("#preview").text("$"+mathparser.parse($("#trainanswer").val()).toLatex()+"$");
			MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
		}
		else if(preview == "chem")
			$("#preview").html(chemparser.parse($("#trainanswer").val()).toHTML());
	});
	
	$('#trainanswer').keypress(function (e) {
		if (e.keyCode == 13) {
			checkAnswer();
		}
	});
	
	loadChallenge(challengeID);
});