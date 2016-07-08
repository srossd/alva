<?php
session_start();
if(isset($_SESSION["id"]))
	header("Location: index.php");
?>
<!DOCTYPE html> 
<html> 
<head> 
	<title>Alva</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.js"></script>
	
	<link rel="stylesheet" href="stylesheet.css" />
	<script src="fullheight.js"></script>
	<script src="login.js"></script>
</head> 
<body> 

<div data-role="page" id="main" data-theme="b">
	<div data-role="header" align="center" class="header">
		<img src="images/logo.png" id="logo"/>
	</div>

	<div data-role="content" class="content">	
		<p><a href="#login" data-role="button">Log In</a></p>	
		<p><a href="#register" data-role="button">Register</a></p>	
	</div>
</div>

<div data-role="page" id="login" data-theme="b">
	<div data-role="header" align="center" class="header">
		<a href="#main" data-role="button" data-icon="home" data-mini="true" data-inline="true" data-iconpos="notext">Main Menu</a>
		<img src="images/logo.png" id="logo" />
	</div>
	
	<div data-role="content" class="content">
		<form id="login" action="auth.php" method="POST" data-ajax="false">
		<?php							
		if(isset($_GET["badlogin"]) && $_GET["badlogin"] == 1)
			echo '<span class="error center" style="width:100px;">Invalid username/password.</span><br /><br />';
		?>
		<div data-role="fieldcontain">
			<label for="email">Email: </label>
			<input type="email" id="email" name="email" />
		</div>
		<div data-role="fieldcontain">
			<label for="pw">Password: </label>
			<input type="password" id="pw" name="pw" />
		</div>
		<a data-role="button" id="submit_login" href="javascript:void(0);" onclick="javascript:login()">Log In</a>
		</form>
	</div>
</div>

<div data-role="page" id="register" data-theme="b">
	<div data-role="header" align="center" class="header">
		<a href="#main" data-role="button" data-icon="home" data-mini="true" data-inline="true" data-iconpos="notext">Main Menu</a>
		<img src="images/logo.png" id="logo" />
	</div>
	
	<div data-role="content" class="content">	
		<form id="register" action="register.php" method="POST" data-ajax="false">
			<span class="error hidden" id="longname">Your Alva name should be less than 10 characters.</span><br />					
			<?php							
			if(isset($_GET["badname"]) && $_GET["badname"] == 1)	
				echo '<span class="error">This name is taken.</span><br />';
			?>						
			<div data-role="fieldcontain">
				<label for="name">Name: </label>
				<input type="text" id="name" name="name" />
			</div>					
			<?php							
			if(isset($_GET["bademail"]) && $_GET["bademail"] == 1)								
				echo '<span class="error">This email address is taken.</span><br />';
			?>		
			<div data-role="fieldcontain">
				<label for="email">Email: </label>
				<input type="email" id="email" name="email" />
			</div>						
			<?php							
			if(isset($_GET["badpass"]) && $_GET["badpass"] == 1)
				echo '<span class="error">Your passwords didn\'t match.</span><br />';				
			?>		
			<div data-role="fieldcontain">
				<label for="pw">Password: </label>
				<input type="password" id="pw" name="pw" />
			</div>
			<div data-role="fieldcontain">
				<label for="pw2">Password again: </label>
				<input type="password" id="pw2" name="pw2" />
			</div>
			<a data-role="button" id="submit_register" href="javascript:void(0);" onclick="javascript:register()">Register</a>
		</form>
	</div>
</div>

</body>
</html>