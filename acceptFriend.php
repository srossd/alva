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

$id = $_GET["id"];
$friendid = $_GET["friendID"];

$query = "INSERT INTO friends (aID, bID) VALUES ({$id},{$friendid}), ({$friendid},{$id})";
mysql_query($query) or die(mysql_error());

$query = "DELETE FROM requests WHERE askerID={$friendid} AND recipientID={$id}";
mysql_query($query);
?>