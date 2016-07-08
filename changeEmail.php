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

$email = $_POST["email"];
$id = $_SESSION["id"];

$existsquery = "SELECT * FROM users WHERE email='{$email}'";
$existsresult = mysql_query($existsquery);

if(mysql_numrows($existsresult) != 0) {
	echo "bademail";
	die();
}

$query = "UPDATE users SET email='{$email}' WHERE ID='{$id}'";
mysql_query($query);

$_SESSION["email"] = $email;
?>