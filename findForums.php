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

$forums = array();

$query = "SELECT * FROM forums ORDER BY name";
$result = mysql_query($query);
while($row = mysql_fetch_assoc($result)) {
	$forums[] = $row;
}

echo json_encode($forums);
?>