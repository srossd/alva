<?php
session_start();
if($_SESSION["room"] != $_GET["room"])
	header("Location: alva.php?room=".$_SESSION["room"]);
if($_GET["room"] == 1)
	header("Location: index.php");
if($_SESSION["image"] == "gravatar")
	$image = "http://www.gravatar.com/avatar/".md5(strtolower(trim($_SESSION["email"])))."?d=".urlencode("http://alvasb.com/images/default.png")."&s=100";
else
	$image = "image.php?image=images/{$_SESSION["image"]}&width=100&height=100";
?>
<html>
	<head>
		<title>Alva - <?php echo $_SESSION["roomname"]; ?></title>
		<link href="favicon.ico" rel="icon" type="image/x-icon" />
		<script src='https://cdn.firebase.com/v0/firebase.js'></script>
		<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"> 
		MathJax.Hub.Config({
    			extensions: ["tex2jax.js"],
    			jax: ["input/TeX","output/HTML-CSS"],
    			tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
  		});
		</script>
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js'></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<script src="https://www.firebase.com/js/libs/idle.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Iceland' rel='stylesheet' type='text/css'>
		
		<link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>

		<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

		<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
		
		<link href='http://fonts.googleapis.com/css?family=Lato:400,400italic|Open+Sans' rel='stylesheet' type='text/css'>
		
		<link href='stylesheet.css' rel='stylesheet' type='text/css'>
		
		<script>
			var id = <?php echo $_SESSION["id"]; ?>;
			var name = "<?php echo $_SESSION["name"]; ?>";
			var image = "<?php echo $image; ?>";
			var roomID = <?php echo $_GET["room"]; ?>;
			var roomName = "<?php echo $_SESSION["roomname"]; ?>";
			var owner = <?php echo $_SESSION["owner"]; ?>
		</script>
		<script src="jquery.xml2json.js"></script>
		<script src="alva.js"></script>  
		<script src="whiteboard.js"></script> 
		<script src="detectMobile.js"></script>
		<script src="redirect.js"></script>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<div id="logo">
					<a href="index.php">
						<img src="logo.png" id="logo" />
					</a>
				</div>
				<div id="roomheader">
					<h1><?php echo $_SESSION["roomname"]; ?></h1>
					<a href="index.php">Back to Dashboard</a>
				</div>
				<div id="nameplatecontainer">
					<div id="nameplate">
						<span id="name"><?php echo $_SESSION["name"]; ?></span>
						<img src="<?php echo $image; ?>" class="nameplate" />
						<img src="images/dropdown_icon.gif" id="dropdown" />
						<div id="dropdown">
							<div class="dropdownevent" id="account">
								Account Settings
							</div>
							<div class="dropdownevent" id="logout">
								Logout
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="chat">
				<div id="messages">
				</div>
				<input type="text" id="message" placeholder="Chat here..."/>
				<div id="users">
				</div>
			</div>
			<div id="questions">
				<div id="tabs-min" class="transbg">
					<ul>					
						<li><a href="#ques">Questions</a></li>
						<li><a href="#board">Whiteboard</a></li>
					</ul>
					<div id="ques">
						<div id="buzzheader" align="center">				
							<a href="#" onclick="next()" class="button" id="next">Next</a>
							<a href="#" onclick="buzz()" class="button hidden" id="buzz">Buzz</a>
							<a href="#" onclick="heart()" class="button" id="heart"><img src="images/save.png" title="Save"/></a>
							<a href="#" onclick="flag()" class="button" id="flag"><img src="images/unflagged.png" title="Flag" /></a>
							<a href="#" onclick="help()" class="button" id="ques"><img src="images/question.png" title="Help" /></a><br /><br />
						</div>		
						<div id="currentquestion">
						</div>
						<div id="bottom">
						</div>
					</div>
					<div id="board">
						<div id="buzzheader" align="center">				
							Pen Size: <div id="pensize"></div>
							<a href="#" onclick="clearBoard()" class="button" id="clear">Clear</a><br /><br />
						</div>		
						<div id="canvas-container">
						  <canvas id="canvas" width="480" height="420"></canvas>
						</div>
						<div id="colorholder"></div>
					</div>
					<div id="questionhistory">
					</div>
				</div>
			</div>
			<div id="owner">
				<div <?php if($_SESSION["owner"] != 1) echo "class='hidden'"; ?>>
					<input type="radio" name="methods" value="random" checked="checked"> Random<br />
					<input type="hidden" id="random" value="">
					<input type="radio" name="methods" value="category"> Category 
					<select id="category">
						<option value="MATH">Math</option>
						<option value="PHYSICS">Physics</option>
						<option value="CHEMISTRY">Chemistry</option>
						<option value="BIOLOGY">Biology</option>
						<option value="EARTH SCIENCE">Earth Science</option>
						<option value="GENERAL SCIENCE">General Science</option>
						<option value="ASTRONOMY">Astronomy</option>
						<option value="COMPUTER SCIENCE">Computer Science</option>
						<option value="ENERGY">Energy</option>
					</select><br />
					<input type="radio" name="methods" value="round"> Set
					<select id="set">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
					</select>
					Round
					<select id="round">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
					</select>
				</div>
				<div id="userpics">
				</div>
			<div id="footer">
			</div>
		</div>
		<div class="hidden">
			<div id="answer">
			</div>
			<div id="help">
				<h2 align="center">Answer Guidelines</h2>
				<p><b>For guidelines not specific to Alva, please see the <a href='http://science.energy.gov/~/media/wdts/nsb/pdf/2014_nsb_rules.pdf' target='_blank'>official rules</a>.</b></p>
				<p>All 2500 Alva questions have been extracted from PDF files available to the public on the website of the National Science Bowl. This process has introduced errors in some questions. While the following guidelines are generally applicable, they are still being implemented and are not yet completely standard.</p>
				<p>
					<ul>
						<li>On multiple choice questions, your answer must either be the letter of the correct choice or the correct choice <i>exactly as it is written</i>.</li>
						<li>On short answer questions involving unordered lists (i.e., "name all of following which are elemental substances: ..."), you will be shown a list of text boxes in which to fill in your response. There will always be enough blanks, but there may be too many. For example, in the question previously stated, if one or more of the substances listed were not elemental, then one or more text boxes should be left blank.</li>
						<li>On short answer questions involving ordered lists, (i.e., "list the following elements in order of increasing atomic mass: ..."), you will be shown a list of text boxes, all of which should be filled with your responses in the order stated in the question.</li>
						<li>For some math questions, you will be shown text boxes in which you should fill in numbers that combine to form a more complex answer. For example, in a question that requires an answer in scientific notation, you will be asked only to fill in the coefficient and the exponent.</li>
					</ul>
				</p>
			</div>
		</div>
	</body>
</html>