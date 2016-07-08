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
$cID = $_POST["cID"];
$question = mysql_real_escape_string($_POST["question"]);
$answer = $_POST["answer"];
$anstype = $_POST["anstype"];
$trailer = mysql_real_escape_string($_POST["trailer"]);
$accuracy = $_POST["accuracy"];

$query = "INSERT INTO challenge_questions (cID, question, answer, anstype, trailer, accuracy) VALUES ({$cID},'{$question}','{$answer}','{$anstype}','{$trailer}','{$accuracy}')";
mysql_query($query);
?>