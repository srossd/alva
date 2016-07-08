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
$name = mysql_real_escape_string($_POST["name"]);
if(strlen($name) >= 10)
	$name = substr($name,0,9);
$email = mysql_real_escape_string($_POST["email"]);
$pw = hash("sha256",$_POST["pw"]);
$pw2 = hash("sha256",$_POST["pw2"]);
if($pw != $pw2) {
	header("Location: login.php?badpass=1");
}
$lookup_query = "SELECT * FROM users WHERE Name='{$name}'";
$result = mysql_query($lookup_query);
if(mysql_numrows($result) > 0) {
	header("Location: login.php?badname=1");
}
$lookup_query = "SELECT * FROM users WHERE Email='{$email}'";
$result = mysql_query($lookup_query);
if(mysql_numrows($result) > 0) {
	header("Location: login.php?bademail=1");
}
else {
	$insert_query = "INSERT INTO users (Name, Email, Password, Image) VALUES ('{$name}','{$email}','{$pw}','default.png')";
	mysql_query($insert_query) or die(mysql_error());
	$get_query = "SELECT * FROM users WHERE email='{$email}'";
	$result = mysql_query($get_query);
	$_SESSION["id"] = mysql_result($result,0,"ID");
	$_SESSION["name"] = mysql_result($result,0,"Name");
	$_SESSION["email"] = $email;
	$_SESSION["image"] = mysql_result($result,0,"Image");
	
	$_SESSION["room"] = 1;
	echo '<meta http-equiv="refresh" content="0;url=index.php">';
}
?>