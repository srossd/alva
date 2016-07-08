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

$id = mysql_real_escape_string($_GET["id"]);
$comments = array();

$query = "SELECT * FROM replies,users AS author WHERE threadID={$id} AND replies.authorID=author.ID ORDER BY votes DESC";
$result = mysql_query($query);

while($row = mysql_fetch_assoc($result)) {
	$image = $row["Image"];
	$email = $row["Email"];
	if($image == "gravatar")
		$image = "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode("http://alvasb.com/images/default.png")."&s=50";
	else
		$image = "image.php?image=images/{$image}&width=50&height=50";
	$row["Image"]=$image;
	date_default_timezone_set('EST');
	$row["time"] = date("F j, Y, g:i a",$row["time"]);
	$comments[] = $row;
}

echo json_encode($comments);
?>