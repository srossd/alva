<?php
session_start();
if(!isset($_SESSION["id"]))
	header("Location: about.php");
?>
<!DOCTYPE html>
<html> 
<head> 
	<title>Alva</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.js"></script>
	<script src="jquery.xml2json.js"></script>
	<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"> 
	MathJax.Hub.Config({
			extensions: ["tex2jax.js"],
			jax: ["input/TeX","output/HTML-CSS"],
			tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
	});
	</script>
	
	<link rel="stylesheet" href="stylesheet.css" />
	<script>
		var id = <?php echo $_SESSION["id"]; ?>;
	</script>
	<script src="fullheight.js"></script>
	<script src="index.js"></script>
</head> 
<body> 

<div data-role="page" id="main" data-theme="b">
	<div data-role="header" align="center" class="header">
		<img src="images/logo.png" id="logo"/>
	</div>

	<div data-role="content" class="content">	
		<p><a href="#freeplay" data-role="button">Free Play</a></p>	
		<p><a href="#mockgame" data-role="button">Mock Game</a></p>	
		<p><a href="#favorite" data-role="button">Favorites</a></p>	
	</div>
</div>

<div data-role="page" id="freeplay" data-theme="b">
	<div data-role="header" align="center" class="header">
		<a href="#main" data-role="button" data-icon="home" data-mini="true" data-inline="true" data-iconpos="notext">Main Menu</a>
		<img src="images/logo.png" id="logo" />
	</div>
	
	<div data-role="content" class="content freeplay">	
		<div class="qbox" id="freeqbox">
		</div>
		<div class="notqbox">
			<div class="ui-content" data-role="popup" id="freepop" data-position-to="origin" data-overlay-theme="a" data-theme="b">
				<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
				<p id="freepopupmessage"></p>
			</div>
			<div class="ui-content" data-role="popup" id="freeanswer" data-position-to="origin" data-overlay-theme="a" data-theme="b">
			</div>
			<p><a href="javascript:void(0);" onclick="nextbuzz()" data-role="button" id="freenb">Next</a></p>
			<p class="center" style="width:85px;"><a href="javascript:void(0);" onclick="flag()" data-role="button" data-inline="true" id="freeflag">Flag</a></p>
		</div>
	</div>
</div>

<div data-role="page" id="mockgame" data-theme="b">
	<div data-role="header" align="center" class="header">
		<a href="#main" data-role="button" data-icon="home" data-mini="true" data-inline="true" data-iconpos="notext">Main Menu</a>
		<img src="images/logo.png" id="logo" />
	</div>
	
	<div data-role="content" class="content mockgame">	
		<div class="qbox" id="mockqbox">
		</div>
		<div class="notqbox">
			<div class='ui-content' data-role="popup" id="mockpop" data-position-to="origin" data-overlayTheme="b">
				<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
				<p id="mockpopupmessage"></p>
			</div>
			<input type="text" class="hidden" id="mockanswer" />
			<p><a href="javascript:void(0);" onclick="nextbuzz()" data-role="button" id="mocknb">Next</a></p>
			<p class="center" style="width:85px;"><a href="javascript:void(0);" onclick="flag()" data-role="button" data-inline="true" id="mockflag">Flag</a></p>
		</div>
	</div>
</div>

<div data-role="page" id="favorite" data-theme="b">
	<div data-role="header" align="center" class="header">
		<a href="#main" data-role="button" data-icon="home" data-mini="true" data-inline="true" data-iconpos="notext">Main Menu</a>
		<img src="images/logo.png" id="logo" />
	</div>
	
	<div data-role="content" class="content">	
		<input type="search" id="favsearch" />
		<div data-role="collapsible-set" id="favqueue">	
		</div>
	</div>
</div>

</body>
</html>