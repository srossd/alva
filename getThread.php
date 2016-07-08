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

$id = mysql_real_escape_string($_GET["id"]);

$query = "SELECT body, votes FROM threads WHERE ID={$id}";
$result = mysql_query($query) or die(mysql_error());
$body = mysql_result($result,0,"body");
$votes = mysql_result($result,0,"votes");
$thread = array();
$thread["body"] = $body;
$thread["votes"] = $votes;
echo json_encode($thread);
?>
