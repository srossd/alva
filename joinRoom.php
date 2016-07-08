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

$id = $_POST["id"];
$roomID = $_POST["roomID"];
$pass = hash("sha256",$_POST["pass"]);
if($pass == hash("sha256",""))
	$pass = "";

$owner_query = "SELECT * FROM owners WHERE userID='{$id}' AND roomID='{$roomID}'";
$owner_result = mysql_query($owner_query);
if(mysql_numrows($owner_result) == 1) {
	$_SESSION["owner"] = 1;
	$_SESSION["room"] = $roomID;
	$query = "SELECT * FROM rooms WHERE ID='{$roomID}' AND Password='{$pass}'";
	$result = mysql_query($query);
	$_SESSION["roomname"] = mysql_result($result,0,"Name");
	die();
}

$query = "SELECT * FROM rooms WHERE ID='{$roomID}' AND Password='{$pass}'";
$result = mysql_query($query);

if(mysql_numrows($result) == 0)
	echo "badpass";
else {
	$_SESSION["owner"] = 0;
	$_SESSION["room"] = $roomID;
	$_SESSION["roomname"] = mysql_result($result,0,"Name");
}
?>