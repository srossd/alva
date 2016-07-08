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
$forumID = mysql_real_escape_string($_POST["forumID"]);
$subject = mysql_real_escape_string($_POST["subject"]);
$body = $_POST["body"];

$query = "INSERT INTO threads (forumID,authorID,subject,body,time) VALUES ({$forumID},{$id},'{$subject}','{$body}',".time().")";
$result = mysql_query($query) or die($query);
echo "thread.php?id=".mysql_result(mysql_query("SELECT ID FROM threads WHERE authorID={$id} ORDER BY time DESC"),0,"ID");
?>