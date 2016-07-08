<?php

$link = mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!$link) {    
	die('Not connected : ' . mysql_error());
}
$db_selected = mysql_select_db($mysql_database, $link);
if (!$db_selected) {    
	die ('Can\'t use foo : ' . mysql_error());
}
$id = $_SESSION["id"];

$query = "SELECT * FROM trainingsets, progress WHERE trainingsets.ID = progress.setID AND progress.userID={$id} AND EXISTS (SELECT * FROM categories WHERE categories.setID=trainingsets.ID AND category='math')";
$result = mysql_query($query) or die(mysql_error());

while($set = mysql_fetch_assoc($result)) {
	echo <<<EOD
	<div class="trainingset" id="{$set["setID"]}">
		<p>{$set["name"]}</p>
		<a onclick='challenge({$set["setID"]});' class='fancybox' href='#challenge'>Challenge</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='javascript:practice({$set["setID"]});'>Practice</a>
		<div class="emptybar">
			<div class="progress"></div>
		</div>
		<script>
			$(".trainingset#{$set["setID"]} .progress").css("width","{$set["progress"]}0%");
		</script>
	</div>
EOD;
}

$query = "SELECT * FROM trainingsets WHERE NOT EXISTS (SELECT * FROM progress WHERE userID={$id} AND setID=ID) AND EXISTS (SELECT * FROM categories WHERE categories.setID=trainingsets.ID AND category='math')";
$result = mysql_query($query) or die(mysql_error());

while($set = mysql_fetch_assoc($result)) {
	echo <<<EOD
	<div class="trainingset" id="{$set["ID"]}">
		<p>{$set["name"]}</p>
		<a onclick='challenge({$set["ID"]});' class='fancybox' href='#challenge'>Challenge</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='javascript:practice({$set["ID"]});'>Practice</a>
		<div class="emptybar">
			<div class="progress"></div>
		</div>
	</div>
EOD;
}
?>