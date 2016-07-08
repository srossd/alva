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
$user1 = $_POST["user1"];
$user2 = $_POST["user2"];
$setID = $_POST["setID"];

$query = "INSERT INTO challenges (setID, user1, user2) VALUES ({$setID},{$user1},{$user2})";
mysql_query($query);
$challenge = mysql_query("SELECT ID FROM challenges ORDER BY ID DESC LIMIT 1");
echo mysql_result($challenge,0,"ID");
?>