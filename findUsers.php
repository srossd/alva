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
$users = array();

if(strlen($search) == 0) {
	echo json_encode($users);
	die();
}

$query = "SELECT * FROM users WHERE Name LIKE '%".$search."%' AND ID <> {$id} AND NOT EXISTS (SELECT * FROM friends WHERE aID=ID AND bID={$id}) LIMIT 0,10";
$result = mysql_query($query);
while($user = mysql_fetch_assoc($result)) {	
	$statusQuery = "SELECT * FROM requests WHERE askerID='{$id}' AND recipientID='".$user["ID"]."'";
	$statusResult = mysql_query($statusQuery);
	if(mysql_numrows($statusResult) > 0) {
		$user["status"] = "friended";
		$user["message"] = mysql_result($statusResult,0,"message");
	}
	else
		$user["status"] = "not friended";
	$users[$user["ID"]] = $user;
}
echo json_encode($users);
?>