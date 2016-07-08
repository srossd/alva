var drawers = {};
var contexts = {};
var penSize = 3;

$(document).ready(function () {
	var currentColor = "000";
	var mouseDown = false;

	var drawersRef = room.child("whiteboard").child("drawers");
	drawersRef.child(name).set({currentColor: currentColor});
	drawersRef.child(name).onDisconnect().remove();
	var actionsRef = room.child("whiteboard").child("actions");
	actionsRef.onDisconnect().remove();

	var myCanvas = $('#canvas')[0];
	var myContext = myCanvas.getContext ? myCanvas.getContext('2d') : null;
	if (myContext == null) {
	  alert("You must use a browser that supports HTML5 Canvas to use the whiteboard.");
	  return;
	}
	contexts[name] = myContext;
	myContext.fillStyle = "#fff";
	myContext.fillRect(0, 0, 480, 420);

	var colors = ["fff","000","f00","0f0","00f","88f","f8d","f88","f05","f80","0f8","cf0","08f","408","ff8","8ff"];
	for (c in colors) {
	  var item = $('<div/>').css("background-color", '#' + colors[c]).addClass("colorbox");
	  item.click((function () {
		var col = colors[c];
		return function () {
		  currentColor = col;
		  drawersRef.child(name).set({currentColor: currentColor});
		};
	  })());
	  item.appendTo('#colorholder');
	}
	
	$("#pensize").slider({
		orientation: "horizontal",
		range: "min",
		min: 1,
		max: 15, 
		value: 3,
		slide: refreshPenSize,
		change: refreshPenSize
	});
	
	$("a#clear").click(function(e) {
		e.preventDefault();
		clearBoard();
	});

	//Keep track of if the mouse is up or down
	$("#canvas").mousedown(function (e) {
		e.preventDefault();
		mouseDown = true;
		if(e.offsetX==undefined)
		{
			xpos = e.pageX-$('#canvas').offset().left;
			ypos = e.pageY-$('#canvas').offset().top;
		}             
		else
		{
			xpos = e.offsetX;
			ypos = e.offsetY;
		}
		actionsRef.push({id: name, action: "mousedown", x: xpos, y: ypos, size: penSize});
	});
	$("#canvas").mousemove(function(e) {
		e.preventDefault();
		if(e.offsetX==undefined)
		{
			xpos = e.pageX-$('#canvas').offset().left;
			ypos = e.pageY-$('#canvas').offset().top;
		}             
		else
		{
			xpos = e.offsetX;
			ypos = e.offsetY;
		}
		if(mouseDown) {
			actionsRef.push({id: name, action: "mousemove", x: xpos, y: ypos, size: penSize});
		}
	});
	$("#canvas").mouseup(function (e) {
		e.preventDefault();
		mouseDown = false;
		if(e.offsetX==undefined)
		{
			xpos = e.pageX-$('#canvas').offset().left;
			ypos = e.pageY-$('#canvas').offset().top;
		}             
		else
		{
			xpos = e.offsetX;
			ypos = e.offsetY;
		}
		actionsRef.push({id: name, action: "mouseup", x: xpos, y: ypos, size: penSize});
	});

	drawersRef.on("value", function(snapshot) {
		drawers = snapshot.val();
		for(drawer_id in drawers) {
			var myCanvas = $('#canvas')[0];
			var myContext = myCanvas.getContext ? myCanvas.getContext('2d') : null;
			contexts[drawer_id] = myContext;
		}
	});

	actionsRef.on("child_added", function(snapshot) {
		var ss = snapshot.val();
		if(ss == "clear") {
			contexts[name].fillStyle = "#fff";
			contexts[name].fillRect(0, 0, 480, 420);
			return;
		}
		var context = contexts[ss.id];
		context.lineJoin = "round";
		context.lineWidth = ss.size;
		context.strokeStyle = "#"+drawers[ss.id].currentColor;
		if(drawers[ss.id].currentColor == "fff")
			context.lineWidth *= 3;
		if(ss.action == "mousedown") {
			context.beginPath();
			context.moveTo(ss.x, ss.y);
		}
		else if(ss.action == "mousemove") {
			context.lineTo(ss.x, ss.y);
			context.stroke();
		}
		else {
			context.lineTo(ss.x, ss.y);
			context.stroke();
		}
	});	
		
});

function refreshPenSize() {
	penSize = $("#pensize").slider("value");
}

function clearBoard() {
	room.child("whiteboard").child("actions").push("clear");
	return false;
}