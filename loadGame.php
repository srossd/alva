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

$questions = array();
$query = "SELECT * FROM questions WHERE flagged=0 ORDER BY RAND() LIMIT 0, 30";
$result = mysql_query($query);
$i = 0;
while($i < mysql_num_rows($result)) {
	mysql_query("UPDATE questions SET seen=1 WHERE ID='".mysql_result($result,$i,"ID")."'");
	$q = mysql_result($result,$i,"category")."<br>".mysql_result($result,$i,"question")."<br>".mysql_result($result,$i,"answer")."<br>".mysql_result($result,$i,"ID");
	$questions[$i] = $q;
	$i++;
}
echo json_encode($questions);
?>
