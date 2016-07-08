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

$forumID = $_GET["forumID"];
$search = mysql_real_escape_string($_GET["query"]);
$recentposts = array();

$query = "SELECT threads.ID, subject, body, Name, time, votes FROM threads LEFT OUTER JOIN users ON threads.authorID=users.ID WHERE forumID={$forumID} AND (subject LIKE '%{$search}%' OR body LIKE '%{$search}%') ORDER BY time DESC LIMIT 10";
$result = mysql_query($query);
while($row = mysql_fetch_assoc($result)) {
	date_default_timezone_set('EST');
	$row["time"] = date("F j, Y, g:i a",$row["time"]);
	$recentposts[] = $row;
}

echo json_encode($recentposts);
?>