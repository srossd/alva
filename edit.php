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
$query = "UPDATE questions SET category='{$_POST["category"]}', question='{$_POST["question"]}', answer='{$_POST["answer"]}', flagged=0 WHERE ID='{$_POST["id"]}'";
mysql_query($query);
echo $query;
?>