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
$search = $_GET["query"];

$favs = array();

$query = "SELECT * FROM questions WHERE ID IN (SELECT qID FROM favorites WHERE userID={$id}) AND (answer LIKE '%".$search."%' OR category LIKE '%".$search."%')";
$result = mysql_query($query);
while($row = mysql_fetch_assoc($result)) {
	$favs[$row["ID"]] = $row;
}

echo json_encode($favs);
?>