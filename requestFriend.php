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

$askerID = $_GET["askerID"];
$recipientID = $_GET["recipientID"];
$message = $_GET["message"];

$existsQuery = "SELECT * FROM requests WHERE askerID='{$askerID}' AND recipientID='{$recipientID}'";
$existsResult = mysql_query($existsQuery);

if(mysql_numrows($existsResult) == 0) {
	$query = "INSERT INTO requests (askerID, recipientID, message) VALUES ('{$askerID}','{$recipientID}','{$message}')";
	mysql_query($query) or die(mysql_error());
}
?>