<?php
session_start();
if(!isset($_SESSION["id"])) {
	header("Location: login.php");
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
		<title>Alva - Account Settings</title>
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
		<link href='stylesheet.css' rel='stylesheet' type='text/css'>

		<script src="settings.js"></script>
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
					<h1>Account Settings</h1>
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
			<div id="settings-container">
				<div id="tabs-min" class="settings-tabs">
					<ul>
						<li><a href="#image">Avatar</a></li>
						<li><a href="#email">Email</a></li>
						<li><a href="#password">Password</a></li>
					</ul>
					<div id="image">
						<div id="gravatar">
							<input type="radio" name="imagetype" value="gravatar" />
							<label for="gravatar"><h3>Use my Gravatar</h3></label>
							<br /><br />
							<img id="gravatar-img" src="<?php echo "http://www.gravatar.com/avatar/".md5(strtolower(trim($_SESSION["email"])))."?d=".urlencode("http://alva.99k.org/images/default3.png")."&s=100"; ?>" width="100" height="100" />
							<br /><br />
							<p>If you see a silhouette, you probably don't have a Gravatar.</p>
							<a target="_blank" href="http://www.gravatar.com">Change my Gravatar</a>
						</div>
						<div id="custom">
							<input type="radio" name="imagetype" value="upload" />
							<label for="upload"><h3>Upload an Image</h3></label>
							<br /><br />
							<img src="
							<?php
								if($_SESSION["image"] == "gravatar")
									echo "image.php?image=images/default.png&width=100&height=100";
								else
									echo "image.php?image=images/{$_SESSION["image"]}&width=100&height=100";
							?>
							" />
							<br /><br />
							<form action="upload_image.php" method="post" enctype="multipart/form-data" id="upload_form">
								<label for="file">Upload new file:</label>
								<input type="file" name="file" id="file" accept="image/*"><br>
								<a class="button" id="upload">Upload</a>
							</form>
						</div>
					</div>
					<div id="email">
						<span class="error hidden" id="bademail">This email address is taken.</span><br />
						<input type="text" id="email-input" value="<?php echo $_SESSION["email"]; ?>" />
						<a class="button" id="change-email">Change Email</a>
						<img src="images/check.png" height="20" id="email-check" class="hidden" />
						<p>Note: changing your email will also change your Gravatar, if applicable.</p>
					</div>
					<div id="password">
						<div align="center">
							<span class="error hidden" id="unmatching">Your passwords do not match.</span>
							<span class="error hidden" id="invalid">Your password is incorrect.</span><br />
						</div>
						<label for="oldpw" class="pw">Old Password:</label>
						<input class="pw" type="password" id="oldpw" /><br />
						<label for="newpw1" class="pw">New Password:</label>
						<input class="pw" type="password" id="newpw1" /><br />
						<label for="newpw2" class="pw">New Password Again:</label>
						<input class="pw" type="password" id="newpw2" /><br />
						<div align="center">
							<a class="button" id="change-password">Change Password</a>
							<img src="images/check.png" height="20" id="password-check" class="hidden" />
						</div>
					</div>
				</div>
			</div>
			<div id="footer">
			</div>
		</div>
	</body>
