<?php
session_start();
if(!isset($_SESSION["id"])) {
	header("Location: /login.php");
}
$_SESSION["roomname"] = "dashboard";
$_SESSION["room"] = 1;
if($_SESSION["image"] == "gravatar")
	$image = "http://www.gravatar.com/avatar/".md5(strtolower(trim($_SESSION["email"])))."?d=".urlencode("images/default.png")."&s=100";
else
	$image = "/image.php?image=images/{$_SESSION["image"]}&width=100&height=100";?>
<html>
	<head>	
		<title>Alva - Dashboard</title>
		<link href="favicon.ico" rel="icon" type="image/x-icon" />
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<script>
			var id = <?php echo $_SESSION["id"]; ?>;
			var name = "<?php echo $_SESSION["name"]; ?>";
			var image = "<?php echo $_SESSION["image"]; ?>";
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
		<link href='/stylesheet.css' rel='stylesheet' type='text/css'>
		
		<!--[if IE 6]>  
		<style>  
		body {behavior: url("csshover3.htc");}  
		</style>  
		<![endif]-->  
	</head>
	<body>
		<div id="container">
			<div id="header">
				<div id="logo">
					<a href="index.php">
						<img src="/logo.png" id="logo" />
					</a>
				</div>
				<div id="roomheader">
				<h1> </h1>
				</div>
				<div id="nameplatecontainer">
					<div id="nameplate">
						<span id="name"><?php echo $_SESSION["name"]; ?></span>
						<img src="<?php echo $image; ?>" class="nameplate" />
						<img src="/images/dropdown_icon.gif" id="dropdown" />
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
			<div id="center-box-container">
				<div id="center-box">
					<h2>Whoops!</h2>
					<p>The page you're looking for doesn't seem to exist. 
					<img src="/images/broken_lightbulb.jpg" align="right" width="200" />
					<a href="/index.php">Go back to the dashboard</a> and try again.
					</p>
				</div>
			</div>
		</div>
	</body>
