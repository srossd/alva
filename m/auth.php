<?php
session_start();
$mysql_host = "srossd.db.11396331.hostedresource.com";
$mysql_database = "srossd";
$mysql_user = "srossd";
$mysql_password = "Mfaimt1701!";
$link = mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!$link) {
    die('Not connected : ' . mysql_error());
}

$db_selected = mysql_select_db($mysql_database, $link);
if (!$db_selected) {
    die ('Can\'t use foo : ' . mysql_error());
}

$email = $_POST["email"];
$pw = hash("sha256",$_POST["pw"]);

$lookup_query = "SELECT * FROM users WHERE Email='{$email}' AND Password='{$pw}'";
$result = mysql_query($lookup_query);

if(mysql_numrows($result) == 1) {
	$_SESSION["id"] = mysql_result($result,0,"ID");
	$_SESSION["name"] = mysql_result($result,0,"Name");
	$_SESSION["email"] = $email;
	$_SESSION["image"] = mysql_result($result,0,"Image");
	$_SESSION["room"] = 1;
	$_SESSION["owner"] = 0;
	header("Location: index.php");
}
else {
	header("Location: about.php?badlogin=1#login");
}
?>