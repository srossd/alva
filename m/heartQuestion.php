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
$query = "INSERT INTO favorites (userID,qID) VALUES ({$_SESSION["id"]},{$_POST["id"]})";
mysql_query($query) or die(mysql_error());
?>