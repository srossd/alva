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
$roomname = mysql_real_escape_string($_POST["roomname"]);
if(strlen($roomname) >= 10)
	$roomname = substr($roomname,0,9);
$roomdesc = mysql_real_escape_string($_POST["roomdesc"]);
$roompriv = $_POST["roompriv"];
$roompass = hash("sha256",$_POST["roompass"]);

$namequery = "SELECT * FROM rooms WHERE Name='{$roomname}'";
$nameresult = mysql_query($namequery);
if(mysql_numrows($nameresult) > 0) {
	die("bad name");
}
	
$query = "INSERT INTO rooms (Name, Description, Private, Password) VALUES ('{$roomname}','{$roomdesc}','{$roompriv}','{$roompass}')";
if($roompass == hash("sha256",""))
	$query = "INSERT INTO rooms (Name, Description, Private, Password) VALUES ('{$roomname}','{$roomdesc}','{$roompriv}','')";
mysql_query($query) or die(mysql_error());

$query = "SELECT LAST_INSERT_ID() AS id;";
$result = mysql_query($query);

$query = "INSERT INTO owners (roomID, userID) VALUES (".mysql_result($result,0,"id").",{$id})";
mysql_query($query);

echo mysql_result($result,0,"id");
?>