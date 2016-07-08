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

$oldpw = hash("sha256",$_POST["old"]);
$newpw1 = hash("sha256",$_POST["new1"]);
$newpw2 = hash("sha256",$_POST["new2"]);

if($newpw1 != $newpw2) {
	echo "unmatching";
	die();
}

$id = $_SESSION["id"];

$existsquery = "SELECT * FROM users WHERE ID='{$id}' AND Password='{$oldpw}'";
$existsresult = mysql_query($existsquery);

if(mysql_numrows($existsresult) == 0) {
	echo "invalid";
	die();
}

$query = "UPDATE users SET Password='{$newpw1}' WHERE ID='{$id}'";
mysql_query($query);
?>