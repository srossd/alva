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

$val = $_POST["val"];
$id = $_SESSION["id"];

$query = "UPDATE users SET image='{$val}' WHERE ID='{$id}'";
mysql_query($query);

$_SESSION["image"] = $val;
?>