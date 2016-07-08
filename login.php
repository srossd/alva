<html>
	<head>
		<title>Alva - Login or Register</title>		
		<link href="favicon.ico" rel="icon" type="image/x-icon" />
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js'></script>		
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script src="jquery.hotkeys.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Lato:400,400italic|Open+Sans' rel='stylesheet' type='text/css'>
		<link href='stylesheet.css' rel='stylesheet' type='text/css'>		
		<script src="login.js"></script>		
		<?php			
		if(isset($_GET["register"]) || isset($_GET["badname"]) || isset($_GET["bademail"]) || isset($_GET["badpass"]))				
			echo '<script>$(document).ready(function(){$("a#registertab").click();});</script>';		
		?>	
	</head>	
	<body>
		<div id="container" class="about">
			<div id="header" class="about">
				<div id="logo">
					<img src="logo.png" id="logo" />
				</div>
			</div>
			<img class="bg" src="images/cover/cover.png" />
			<div id="left">
				&nbsp;
			</div>
			<div id="middle">
				<div id="logincontainer">
<div id="logintabs">	
				<ul>					
					<li><a href="#login">Login</a></li>
					<li><a id="registertab" href="#register">Register</a></li>
				</ul>	
				<div id="login">
					<br /><br />
					<form id="login" action="auth.php" method="post">
						<?php							
						if(isset($_GET["badlogin"]) && $_GET["badlogin"] == 1)
							echo '<span class="error">Invalid username/password.</span><br /><br />';
						?>
						<label class="login" for="email">Email</label>
						<input class="login shortcut" type="text" name="email" id="email" />
						<br /><br />						
						<label class="login" for="pw">Password</label>	
						<input class="login shortcut" type="password" name="pw" id="pw" />	
						<br /><br />						
						<a class="button" onclick="javascript:login();">Submit</a>
					</form>				
				</div>				
			<div id="register">			
				<form id="register" action="register.php" method="post">						
					<?php							
					if(isset($_GET["badname"]) && $_GET["badname"] == 1)	
						echo '<span class="error">This name is taken.</span><br />';
					?>						
					<span class="error hidden" id="longname">This should be less than 10 characters.</span><br />
					<label class="login" for="email">Name</label>
					<input class="login" type="text" name="name" id="name" title="This is the name you will go by on Alva."/>
					<br /><br />						
					<?php							
					if(isset($_GET["bademail"]) && $_GET["bademail"] == 1)								
						echo '<span class="error">This email address is taken.</span><br />';
					?>						
					<label class="login" for="email">Email</label>	
					<input class="login" type="text" name="email" id="email" />	
					<br /><br />						
					<?php							
					if(isset($_GET["badpass"]) && $_GET["badpass"] == 1)
					echo '<span class="error">Your passwords didn\'t match.</span><br />';				
					?>						
					<label class="login" for="pw">Password</label>	
					<input class="login" type="password" name="pw" id="pw" />	
					<br /><br />						
					<label class="login" for="pw">Password Again</label>	
					<input class="login" type="password" name="pw2" id="pw2" />
					<br /><br />						
					<a class="button" onclick="javascript:register();">Submit</a>		
				</form>			
			</div>						
			</div>
			<div id="right">
			</div>
			<div id="footer">
			</div>
		</div>
	</body>
</html>