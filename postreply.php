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

$id = mysql_real_escape_string($_POST["id"]);
$threadID = mysql_real_escape_string($_POST["threadID"]);
$body = mysql_real_escape_string($_POST["body"]);

$query = "INSERT INTO replies (threadID,authorID,body,time) VALUES ({$threadID},{$id},'{$body}',".time().")";
$result = mysql_query($query) or die($query);
echo "thread.php?id=".$threadID;
?>