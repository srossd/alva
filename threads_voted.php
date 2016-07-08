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

$id = $_GET["id"];
$voted = array();

$query = "SELECT threadID FROM thread_votes WHERE userID={$id}";
$result = mysql_query($query);
while($row = mysql_fetch_assoc($result)) {
	$voted[] = $row["threadID"];
}

echo json_encode($voted);
?>