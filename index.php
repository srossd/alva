<?php
session_start();
if(!isset($_SESSION["id"])) {
	header("Location: about.php");
}
$_SESSION["roomname"] = "dashboard";
$_SESSION["room"] = 1;
if($_SESSION["image"] == "gravatar")
	$image = "http://www.gravatar.com/avatar/".md5(strtolower(trim($_SESSION["email"])))."?d=".urlencode("http://alvasb.com/images/default.png")."&s=100";
else
	$image = "image.php?image=images/{$_SESSION["image"]}&width=100&height=100";?>
<html>
	<head>	
		<title>Alva - Simulator</title>
		<link href="favicon.ico" rel="icon" type="image/x-icon" />
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<script>
			var id = <?php echo $_SESSION["id"]; ?>;
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

		<script src="dashboard.js"></script>
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
					<div class="nav bold" id="simulator">Simulator</div>
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
			<div id="friends">
				<h1 align="center">Friends</h1>
				<div align="center">
					<a href="#newfriend" class="fancybox button">Add Friend</a><br><br>
					<div id="requests">
					</div>
				</div>
				<input type="text" id="friendsearch" class="search" name="friendsearch" placeholder="Search..."><br><br>
				<div id="friendqueue" class="queue">
				</div>
			</div>
			<div id="rooms">				
				<h1 align="center">Rooms</h1>
				<div align="center">
					<a href="#newroom" class="fancybox button">Create Room</a><br><br>
				</div>
				<div id="tabs-ultramin">
					<ul>					
						<li><a href="#my">My Rooms</a></li>
						<li><a href="#fav">Favorites</a></li>
						<li><a href="#other">Other Rooms</a></li>
					</ul>
					<div id="my">
						<input type="text" id="myroomsearch" class="search" name="roomsearch" placeholder="Search..."><br><br>
						<div id="myroomqueue" class="queue">
						</div>
					</div>
					<div id="fav">
						<input type="text" id="favroomsearch" class="search" name="roomsearch" placeholder="Search..."><br><br>
						<div id="favroomqueue" class="queue">
						</div>
					</div>
					<div id="other">
						<input type="text" id="otherroomsearch" class="search" name="roomsearch" placeholder="Search..."><br><br>
						<div id="otherroomqueue" class="queue">
						</div>
					</div>
				</div>
			</div>
			<div id="stats">
				<h1 align="center">Saved Questions</h1>
				<input type="text" id="favsearch" class="search" name="favsearch" placeholder="Search..."><br><br>
				<div id="favqueue" class="queue">
				</div>
			</div>
			<div id="footer">
			</div>
		</div>
		<div style="display:none;">
			<div id="newfriend" align="center">
				<input type="text" id="usersearch" class="search" name="usersearch" placeholder="Search..."><br><br>
				<div id="userqueue" class="queue">
				</div>
			</div>
			<div id="newroom" align="center">
				<form>						
					<span class="error hidden" id="badname">This name is taken.</span><br />		
					<span class="error hidden" id="longname">This should be less than 10 characters.</span><br />			
					<label class="login" for="roomname">Name</label>
					<input class="login" type="text" name="roomname" id="roomname"/>
					<br /><br />
					<label class="login" for="roomdesc">Description</label>	
					<input class="login" type="text" name="roomdesc" id="roomdesc" />	
					<br /><br />			
					<label class="login" for="roompriv" title="If you check this box, your room will not be displayed when people search for it.">Private</label>
					<input class="login" type="checkbox" name="roompriv" id="roompriv" />	
					<br /><br />						
					<label class="login" for="pw">Password</label>	
					<input class="login" type="password" name="roompass" id="roompass" />
					<br /><br />						
					<a class="button" onclick="createRoom()">Create Room</a>		
				</form>	
			</div>
		</div>
	</body>
