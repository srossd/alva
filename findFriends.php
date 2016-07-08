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
$search = $_GET["query"];
$friends = array();
$query = "SELECT ID, Name, Email, Image FROM users WHERE EXISTS (SELECT * FROM friends WHERE aID=users.ID AND bID='".$id."' AND (SELECT Name FROM users WHERE ID=friends.aID) LIKE '%".$search."%')";
$result = mysql_query($query);
while($row = mysql_fetch_assoc($result)) {		
	$image = $row["Image"];
	$email = $row["Email"];
	if($image == "gravatar")
		$image = "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode("http://alvasb.com/images/default.png")."&s=100";
	else
		$image = "image.php?image=images/{$image}&width=100&height=100";
	$row["Image"] = $image;
	$friends[] = $row;
}
echo json_encode($friends);
?>