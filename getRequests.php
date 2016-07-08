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

$requests = array();

$query = "SELECT * FROM requests WHERE recipientID='{$id}'";
$result = mysql_query($query) or die(mysql_error());

while($row = mysql_fetch_assoc($result)) {
	$namequery = "SELECT Name FROM users WHERE ID='".$row["askerID"]."'";
	$nameresult = mysql_query($namequery);
	$row["name"] = mysql_result($nameresult,0,"Name");
	$requests[$row["askerID"]] = $row;
}

echo json_encode($requests);
?>