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
?>
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
		<link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>

		<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

		<link rel="stylesheet" href="/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
		<script type="text/javascript" src="/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
		
		<link href='http://fonts.googleapis.com/css?family=Lato:400,400italic|Open+Sans' rel='stylesheet' type='text/css'>
		
		<link href='stylesheet.css' rel='stylesheet' type='text/css'>

		<script src="trainer.js"></script>
		<script src="detectMobile.js"></script>
		<script src="redirect.js"></script>
		<style>
		  .ui-tabs-vertical { width: 55em;}
		  .ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 12em;}
		  .ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
		  .ui-tabs-vertical .ui-tabs-nav li a { display:block; }
		  .ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-right: .1em; border-right-width: 1px; border-right-width: 1px; margin: 0 -1px .2em 0; }
		  .ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: right; width: 35em;}
		</style>
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
				<div id="subjects">
				  <ul>
					<li><a href="#math">Math</a></li>
					<li><a href="#physics">Physics</a></li>
					<li><a href="#chemistry">Chemistry</a></li>
					<li><a href="#challenges">Challenges</a></li>
				  </ul>
				  <div id="math">
					<?php
						include "mathsets.php";
					?>
				  </div>
				  <div id="physics">
					<?php
						include "physicssets.php";
					?>
				  </div>
				  <div id="chemistry">
					<?php
						include "chemsets.php";
					?>
				  </div>
				  <div id="challenges">
					<table width="100%" cellpadding="5px">
					</table>
				  </div>
				</div>
				<div id="topranks">
				<h3 align='center'>Rankings</h3>
				<table></table>
				<p><a href='ranking.php'>See all rankings</a></p>
				</div>
			</div>
			<div id="footer">
			</div>
			<div class="hidden">
				<div id="challenge" align="center">
					<h3 id="title"></h3>
					<div id="userselect"></div><br /><br />
					<a href="javascript:submitChallenge();">Challenge</a>
					<input type="hidden" id="setID" />
				</div>
			</div>
		</div>
	</body>
