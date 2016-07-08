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

$challenges = array();

$query = "SELECT ID, setID, (SELECT name FROM trainingsets WHERE ID=setID) AS setName, user1, (SELECT Name FROM  users WHERE ID=user1) AS user1name, user2, (SELECT Name FROM  users WHERE ID=user2) AS user2name, score1, score2 FROM challenges WHERE user1={$id} OR user2={$id}";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_assoc($result)) {
	if($row["user2"] == $id) {
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
	
	$type = "unknown";
	if($row["score1"] == -1 || $row["score2"] == -1)
		$type = "unknown";
	else if($row["score1"] > $row["score2"])
		$type = "win";
	else if($row["score1"] < $row["score2"])
		$type = "lose";
	
	$name = $row["user2name"];
	$setName = $row["setName"];
	
	if($row["score1"] == -1)
		$row["score1"] = "<a href='javascript:playChallenge(".$row["ID"].")'>?</a>";
	if($row["score2"] == -1)
		$row["score2"] = "?";
	$score = $row["score1"]."-".$row["score2"];
	
	$challenges[] = array("type" => $type, "name" => $name, "setName" => $setName, "score" => $score);
}
echo json_encode($challenges);
?>