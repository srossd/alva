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
$query = "DELETE FROM favorites WHERE userID={$_SESSION["id"]} AND qID={$_POST["id"]}";
mysql_query($query) or die(mysql_error());
?>