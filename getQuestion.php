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

$method = $_GET["method"];
$param = $_GET["param"];
$qid = $_GET["qid"];

if($method == "random") {
	$query = "SELECT * FROM questions ORDER BY RAND()";
	$result = mysql_query($query);
	echo mysql_result($result,0,"category")."<br>".mysql_result($result,0,"question")."<br>".mysql_result($result,0,"answer")."<br>".mysql_result($result,0,"ID")."<br>".mysql_result($result,0,"format");
}
else if($method == "category") {
	$query = "SELECT * FROM questions WHERE category LIKE '%{$param}%' ORDER BY RAND()";
	$result = mysql_query($query);
	echo mysql_result($result,0,"category")."<br>".mysql_result($result,0,"question")."<br>".mysql_result($result,0,"answer")."<br>".mysql_result($result,0,"ID")."<br>".mysql_result($result,0,"format");
}	
else if($method == "round") {
	$query = "SELECT * FROM questions WHERE `set` = '{$_GET["set"]}' AND round = '{$param}' AND ID > {$qid} ORDER BY ID";
	$result = mysql_query($query);
	if(mysql_numrows($result) == 0)
		$result = mysql_query("SELECT * FROM questions WHERE `set` = '{$_GET["set"]}' AND round = '{$param}' ORDER BY ID") or die($query);
	echo mysql_result($result,0,"category")."<br>".mysql_result($result, 0, "question")."<br>".mysql_result($result,0,"answer")."<br>".mysql_result($result,0,"ID")."<br>".mysql_result($result,0,"format");
}	
?>