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

$collision_query = "SELECT * FROM users WHERE Name={$_POST["name"]}";
$result = mysql_query($collision_query);
$pw = hash("sha256",$_POST["pw"]);
if(mysql_numrows($result) != 0) {
	echo "collision";
}
else {
	$insert_query = "INSERT INTO users (Name,Password) VALUES ('{$_POST["name"]}','{$pw}')";
	mysql_query($insert_query);
	echo "success";
}
?>