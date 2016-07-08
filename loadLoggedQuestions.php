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
$cID = $_GET["cID"];

$questions = array();
$query = "SELECT question, answer, anstype, trailer, accuracy FROM challenge_questions WHERE cID={$cID} ORDER BY ID ASC";
$result = mysql_query($query);
if(mysql_numrows($result) == 0)
	die();

while($row = mysql_fetch_assoc($result))
	$questions[] = $row;

echo json_encode($questions);
?>