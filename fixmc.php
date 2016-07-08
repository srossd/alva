<?php
include 'sqlheader.php';

$query = "SELECT * FROM questions WHERE category LIKE '%Multiple%'";
$result = mysql_query($query);
$i = 0;
while($i < mysql_numrows($result)) {
	$question = mysql_result($result,$i,"question");
	$answer = mysql_result($result,$i,"answer");
	$pos1 = strpos($answer,"\"1\": \"");
	$letter = substr($answer,$pos1+7,1);
	$pos2 = strpos($question,$letter.")");
	$pos3 = strpos($question,"<br/>",$pos2);
	if(!$pos3)
		$pos3 = strlen($question);
	$scianswer = trim(substr($question,$pos2+2,$pos3-$pos2-2));
	$scianswer = json_encode(mysql_real_escape_string(preg_quote($scianswer)));
	$scianswer = substr($scianswer,1,strlen($scianswer)-2);
	$newanswer = "{\"model\": \"{$letter}\",\"1\": \"({$letter}|{$scianswer})\"}";
	$update = "UPDATE questions SET answer='{$newanswer}' WHERE ID='".mysql_result($result,$i,"ID")."'";
	mysql_query($update);
	$i++;
}
?>