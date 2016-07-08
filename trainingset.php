<?php
include 'sqlheader.php';

if(!isset($_SESSION["id"])) {
	header("Location: about.php");
}
$_SESSION["roomname"] = "dashboard";
$_SESSION["room"] = 1;
if($_SESSION["image"] == "gravatar")
	$image = "http://www.gravatar.com/avatar/".md5(strtolower(trim($_SESSION["email"])))."?d=".urlencode("http://alvasb.com/images/default.png")."&s=100";
else
	$image = "image.php?image=images/{$_SESSION["image"]}&width=100&height=100";

$query = "SELECT * FROM trainingsets WHERE ID={$_GET["id"]}";
$result = mysql_query($query);
if(mysql_numrows($result) == 0)
	die();
$name = mysql_result($result,0,"name");
$file = mysql_result($result,0,"file");

$query = "SELECT * FROM progress WHERE userID={$_SESSION["id"]} AND setID={$_GET["id"]}";
$result = mysql_query($query);
$progress = 0;
if(mysql_numrows($result) == 1)
	$progress = mysql_result($result,0,"progress");
?>
<html>
	<head>	
		<title>Alva - <?php echo $name; ?></title>
		<link href="favicon.ico" rel="icon" type="image/x-icon" />
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<script>
			const id = <?php echo $_SESSION["id"]; ?>;
			var setID = <?php echo $_GET["id"]; ?>;
			var name = "<?php echo $name; ?>";
			var file = "<?php echo $file; ?>";
			var progress = <?php echo $progress; ?>;
		</script>
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js'></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>

		<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

		<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
		
		<link href='http://fonts.googleapis.com/css?family=Lato:400,400italic|Open+Sans' rel='stylesheet' type='text/css'>
		
		<link href='stylesheet.css' rel='stylesheet' type='text/css'>
		
		<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"> 
		MathJax.Hub.Config({
    			extensions: ["tex2jax.js"],
    			jax: ["input/TeX","output/HTML-CSS"],
    			tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
  		});
		</script>
		
		<script src="jquery.xml2json.js"></script>
		
		<script src="peg.js"></script>
		<script src="math.js"></script>
		<script src="chem.js"></script>
		<script src="trainingset.js"></script>
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
					<div class="nav" id="community">Community</div>		
					<div class="nav" id="simulator">Simulator</div>
					<div class="nav bold" id="trainer">Trainer</div>				
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
			<div id="fullpage">
				<div id="alvatrainer">
					<p id="question"></p>
					<input type="text" id="trainanswer" />
					<span id="trailer"></span>
					<a id="checkans" href="javascript:checkAnswer();" class="button">Submit</a><br />
					<div id="preview"></div>
					<p id="message"></p>
					<p id="solution"></p>
					<div class="emptybar">
						<div class="progress"></div>
					</div>
				</div>
				</div>
			</div>
			<div id="footer">
			</div>
		</div>
		<div class="hidden">
			<div id="challenge">
			</div>
		</div>
	</body>
</html>