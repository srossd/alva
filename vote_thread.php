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

$id = mysql_real_escape_string($_POST["id"]);
$threadID = mysql_real_escape_string($_POST["threadID"]);
$vote = mysql_real_escape_string($_POST["vote"]);

$query = "SELECT * FROM thread_votes WHERE userID={$id} AND threadID={$threadID}";
if(mysql_numrows(mysql_query($query)) > 0)
	die();

$query = "INSERT INTO thread_votes (userID,threadID,vote) VALUES ({$id},{$threadID},{$vote})";
$result = mysql_query($query) or die($query);

$query = "UPDATE threads SET votes=votes".($vote == 1 ? "+1" : "-1")." WHERE ID={$threadID}";
mysql_query($query);
?>