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

$query = "SELECT ID, setID, (SELECT name FROM trainingsets WHERE ID=setID) AS setName, (SELECT file FROM trainingsets WHERE ID=setID) AS setFile, user1, (SELECT Name FROM  users WHERE ID=user1) AS user1name, user2, (SELECT Name FROM  users WHERE ID=user2) AS user2name, score1, score2 FROM challenges WHERE ID = {$cID}";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_assoc($result)) {
	if($row["user2"] == $_SESSION["id"]) {
		$temp1 = $row["user1"];
		$temp2 = $row["user1name"];
		$temp3 = $row["score1"];
		$row["user1"] = $row["user2"];
		$row["user1name"] = $row["user2name"];
		$row["score1"] = $row["score2"];
		$row["user2"] = $temp1;
		$row["user2name"] = $temp2;
		$row["score2"] = $temp3;
	}
	echo json_encode($row);
}
?>