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

$query = "SELECT * FROM questions WHERE flagged=1 ORDER BY ID";
$result = mysql_query($query);
echo mysql_result($result,0,"category")."<br>".mysql_result($result,0,"question")."<br>".mysql_result($result,0,"answer")."<br>".mysql_result($result,0,"ID");
?>