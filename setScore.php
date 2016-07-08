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
$id = $_POST["id"];
$score = $_POST["score"];

mysql_query("UPDATE challenges SET score1={$score} WHERE ID={$cID} AND user1={$id}");
mysql_query("UPDATE challenges SET score2={$score} WHERE ID={$cID} AND user2={$id}");

$user = 1;
if(mysql_numrows(mysql_query("SELECT * FROM challenges WHERE ID={$cID} AND user1={$id}")) == 0)
	$user = 2;

$challenge = mysql_query("SELECT * FROM challenges WHERE ID={$cID}");
$setID = mysql_result($challenge,0,"setID");
$user1 = mysql_result($challenge,0,"user1");
$user2 = mysql_result($challenge,0,"user2");
$score1 = mysql_result($challenge,0,"score1");
$score2 = mysql_result($challenge,0,"score2");
if($score1 >= 0 && $score2 >= 0) {
	$Ra = 1000;
	$Rb = 1000;
	$raresult = mysql_query("SELECT ranking FROM rankings WHERE userID={$user1} AND setID={$setID}");
	if(mysql_numrows($raresult) == 1)
		$Ra = mysql_result($raresult,0,"ranking");
	$rbresult = mysql_query("SELECT ranking FROM rankings WHERE userID={$user2} AND setID={$setID}");
	if(mysql_numrows($rbresult) == 1)
		$Rb = mysql_result($rbresult,0,"ranking");
	$Ka = ($Ra > 1200 ? 15 : 30);
	$Kb = ($Rb > 1200 ? 15 : 30);
	$Qa = pow(10,$Ra/300);
	$Qb = pow(10,$Rb/300);
	$Ea = $Qa/($Qa+$Qb);
	$Eb = $Qb/($Qa+$Qb);
	$Sa = 0;
	$Sb = 0;
	if($score1 > $score2)
		$Sa = 1;
	else if($score1 == $score2) {
		$Sa = .5;
		$Sb = .5;
	}
	else
		$Sb = 1;
	
	$Ra += $Ka*($Sa-$Ea);
	$Rb += $Kb*($Sb-$Eb);
	if(mysql_numrows($raresult) == 1)
		mysql_query("UPDATE rankings SET ranking={$Ra} WHERE userID={$user1} AND setID={$setID}");
	else
		mysql_query("INSERT INTO rankings (userID,setID,ranking) VALUES ({$user1},{$setID},{$Ra})");
	if(mysql_numrows($rbresult) == 1)
		mysql_query("UPDATE rankings SET ranking={$Rb} WHERE userID={$user2} AND setID={$setID}");
	else
		mysql_query("INSERT INTO rankings (userID,setID,ranking) VALUES ({$user2},{$setID},{$Rb})");
	
	if($user == 1)
		echo round($Ra);
	else
		echo round($Rb);
		
	$sets = mysql_query("SELECT * FROM categories");
	$matha = 0;
	$mathb = 0;
	$chema = 0;
	$chemb = 0;
	$physa = 0;
	$physb = 0;
	$nmath = 0;
	$nchem = 0;
	$nphys = 0;
	while($set = mysql_fetch_assoc($sets)) {
		if($set["category"] == "math") {
			$nmath++;
			$ranka = mysql_query("SELECT ranking FROM rankings WHERE setID={$set["setID"]} AND userID={$user1}");
			$rankb = mysql_query("SELECT ranking FROM rankings WHERE setID={$set["setID"]} AND userID={$user2}");
			if(mysql_numrows($ranka) == 0)
				$matha += 1000;
			else
				$matha += mysql_result($ranka,0,"ranking");
			if(mysql_numrows($rankb) == 0)
				$mathb += 1000;
			else
				$mathb += mysql_result($rankb,0,"ranking");
		}
		else if($set["category"] == "chemistry") {
			$nchem++;
			$ranka = mysql_query("SELECT ranking FROM rankings WHERE setID={$set["setID"]} AND userID={$user1}");
			$rankb = mysql_query("SELECT ranking FROM rankings WHERE setID={$set["setID"]} AND userID={$user2}");
			if(mysql_numrows($ranka) == 0)
				$chema += 1000;
			else
				$chema += mysql_result($ranka,0,"ranking");
			if(mysql_numrows($rankb) == 0)
				$chemb += 1000;
			else
				$chemb += mysql_result($rankb,0,"ranking");
		}
		else if($set["category"] == "physics") {
			$nphys++;
			$ranka = mysql_query("SELECT ranking FROM rankings WHERE setID={$set["setID"]} AND userID={$user1}");
			$rankb = mysql_query("SELECT ranking FROM rankings WHERE setID={$set["setID"]} AND userID={$user2}");
			if(mysql_numrows($ranka) == 0)
				$physa += 1000;
			else
				$physa += mysql_result($ranka,0,"ranking");
			if(mysql_numrows($rankb) == 0)
				$physb += 1000;
			else
				$physb += mysql_result($rankb,0,"ranking");
		}
	}
	$matha = $matha/$nmath;
	$mathb = $mathb/$nmath;
	$physa = $physa/$nphys;
	$physb = $physb/$nphys;
	$chema = $chema/$nchem;
	$chemb = $chemb/$nchem;
	$avga = ($matha+$physa+$chema)/3;
	$avgb = ($mathb+$physb+$chemb)/3;
	if(mysql_numrows(mysql_query("SELECT * FROM composite_rankings WHERE userID={$user1}")) > 0)
		mysql_query("UPDATE composite_rankings SET math={$matha}, chemistry={$chema}, physics={$physa}, average={$avga} WHERE userID={$user1}");
	else
		mysql_query("INSERT INTO composite_rankings (userID,math,chemistry,physics,average) VALUES ({$user1},{$matha},{$chema},{$physa},{$avga})");
	if(mysql_numrows(mysql_query("SELECT * FROM composite_rankings WHERE userID={$user2}")) > 0)
		mysql_query("UPDATE composite_rankings SET math={$mathb}, chemistry={$chemb}, physics={$physb}, average={$avgb} WHERE userID={$user2}");
	else
		mysql_query("INSERT INTO composite_rankings (userID,math,chemistry,physics,average) VALUES ({$user2},{$mathb},{$chemb},{$physb},{$avgb})");
}
?>