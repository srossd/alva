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

$rooms = array();

$query = "SELECT * FROM rooms WHERE private=0 AND ID IN (SELECT roomID FROM owners WHERE userID={$id}) AND (Name LIKE '%".$search."%' OR Description LIKE '%".$search."%')";
$myrooms = mysql_query($query);
while($row = mysql_fetch_assoc($myrooms)) {
	$rooms["myrooms"][] = $row;
}

$query = "SELECT * FROM rooms WHERE private=0 AND ID NOT IN (SELECT roomID FROM owners WHERE userID={$id}) AND (Name LIKE '%".$search."%' OR Description LIKE '%".$search."%') AND ID NOT IN (SELECT roomID FROM favorite_rooms WHERE userID={$id})";
$otherrooms = mysql_query($query);
while($row = mysql_fetch_assoc($otherrooms)) {
	$rooms["otherrooms"][] = $row;
}

$query = "SELECT * FROM rooms WHERE private=0 AND ID NOT IN (SELECT roomID FROM owners WHERE userID={$id}) AND (Name LIKE '%".$search."%' OR Description LIKE '%".$search."%') AND ID IN (SELECT roomID FROM favorite_rooms WHERE userID={$id})";
$favrooms = mysql_query($query);
while($row = mysql_fetch_assoc($favrooms)) {
	$rooms["favrooms"][] = $row;
}

echo json_encode($rooms);
?>