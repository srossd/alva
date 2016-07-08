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

$ranking = array();

$query = "SELECT ID, name, email, image, (SELECT math FROM composite_rankings WHERE userID=ID) AS math, (SELECT physics FROM composite_rankings WHERE userID=ID) AS physics, (SELECT chemistry FROM composite_rankings WHERE userID=ID) AS chemistry, (SELECT average FROM composite_rankings WHERE userID=ID) AS score FROM users WHERE EXISTS (SELECT * FROM composite_rankings WHERE userID=ID) ORDER BY score DESC";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_assoc($result)) {
	$image = $row["image"];
	$email = $row["email"];
	if($image == "gravatar")
		$image = "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode("http://alvasb.com/images/default.png")."&s=50";
	else
		$image = "image.php?image=images/{$image}&width=50&height=50";
	$row["image"] = $image;
	$ranking[] = $row;
}

echo json_encode($ranking);
?>