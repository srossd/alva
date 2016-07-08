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
	
$link = mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!$link) {
    die('Not connected : ' . mysql_error());
}
$db_selected = mysql_select_db($mysql_database, $link);
if (!$db_selected) {
    die ('Can\'t use foo : ' . mysql_error());
}

$id = mysql_real_escape_string($_GET["id"]);
$query = "SELECT * FROM forums WHERE ID={$id}";
$result = mysql_query($query);
$name = mysql_result($result,0,"name");
?>
<html>
	<head>	
		<title>Alva - <?php echo $name; ?></title>
		<link href="favicon.ico" rel="icon" type="image/x-icon" />
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<script>
			var id = <?php echo $_SESSION["id"]; ?>;
			var forumID = <?php echo $id; ?>;
			var name = "<?php echo $_SESSION["name"]; ?>";
			var image = "<?php echo $image; ?>";
		</script>
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js'></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script src='https://cdn.firebase.com/v0/firebase.js'></script>
		<script src="https://www.firebase.com/js/libs/idle.js"></script>
		<link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>

		<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

		<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
		
		<link href='http://fonts.googleapis.com/css?family=Lato:400,400italic|Open+Sans' rel='stylesheet' type='text/css'>
		
		<link href='stylesheet.css' rel='stylesheet' type='text/css'>

		<script src="forum.js"></script>
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
					<div class="nav bold" id="community">Community</div>			
					<div class="nav" id="simulator">Simulator</div>
					<div class="nav" id="trainer">Trainer</div>				
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
			<div id="left">
				<h1 align="center">My Posts</h1>
				<input type="text" id="mysearch" class="search" name="mysearch" placeholder="Search..." style="margin-top:54px;"><br><br>
				<div id="myqueue" class="queue">
				</div>
			</div>
			<div id="middle">				
				<h1 align="center"><?php echo $name; ?></h1>
				<div align="center">
					<a href="newthread.php?id=<?php echo $id; ?>" class="button">New Thread</a><br><br>
				</div>
				<input type="text" id="threadsearch" class="search" name="threadsearch" placeholder="Search..."><br><br>
				<div id="threadqueue" class="queue">
				</div>
			</div>
			<div id="right">
				<h1 align="center">Recent Posts</h1>
				<input type="text" id="recentsearch" class="search" name="recentsearch" placeholder="Search..." style="margin-top:54px;"><br><br>
				<div id="recentqueue" class="queue">
				</div>
			</div>
			<div id="footer">
			</div>
		</div>
	</body>
