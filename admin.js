var id = "";
var category = "";

function submitEdit() {
	$.post("edit.php",{id: id, category: category, question: $("#question").val().replace("\\\\","\\"), answer: $("#answer").val()},function() {
		getNextQuestion();
	});
}

function getNextQuestion() {
	$.get("getFlaggedQuestion.php",function(message) {
		var parts = message.split("<br>");
		id = parts[3];
		category = parts[0];
		$("#question").val(parts[1]);
		$("#answer").val(parts[2]);
		$("#preview").html($("textarea#question").val());
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	});
}

$(document).ready(function() {
	getNextQuestion();
	$("textarea#question").keyup(function() {
		$("#preview").html($("textarea#question").val());
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	});
});