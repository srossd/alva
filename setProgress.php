<?php
include 'sqlheader.php';

$link = mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!$link) {    
	die('Not connected : ' . mysql_error());
}
$db_selected = mysql_select_db($mysql_database, $link);
if (!$db_selected) {    
	die ('Can\'t use foo : ' . mysql_error());
}
$userID = $_POST["userID"];
$setID = $_POST["setID"];
$progress = $_POST["progress"];

$query = "SELECT * FROM progress WHERE setID={$setID} AND userID={$userID}";
$result = mysql_query($query) or die(mysql_error());
if(mysql_numrows($result) == 0) {
	$query = "INSERT INTO progress (setID, userID, progress) VALUES ({$setID},{$userID},{$progress})";
	mysql_query($query);
}
else {
	$query = "UPDATE progress SET progress={$progress} WHERE setID={$setID} AND userID={$userID}";
	mysql_query($query);
}
?>