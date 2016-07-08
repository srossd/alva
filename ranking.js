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
	loadRanking();
});

function loadRanking() {
	$.get("getRanking.php",function(response) {
		var ranking = $.parseJSON(response);
		displayRanking(ranking);
	});
}

function displayRanking(ranking) {
	for(var i = 0; i < ranking.length; i++) {
		var row = ranking[i];
		var rank = "<tr class='"+(i % 2 == 0 ? "even" : "odd")+"'>";
		rank += "<td>"+(i+1)+"</td>";
		rank += "<td><img width='50' src='"+row.image+"'></td>";
		rank += "<td>"+row.name+"</td>";
		rank += "<td>"+row.math+"</td>";
		rank += "<td>"+row.physics+"</td>";
		rank += "<td>"+row.chemistry+"</td>";
		rank += "<td>"+row.score+"</td></tr>";
		$("#ranking").append(rank);
	}
}