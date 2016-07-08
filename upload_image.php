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

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = strtolower(end($temp));
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& in_array(strtolower($extension), $allowedExts))
{
	if ($_FILES["file"]["error"] > 0)
    {
		echo "Error: " . $_FILES["file"]["error"] . "<br>";
    }
	else
    {
		$random = substr(md5(rand()),0,7);
		while(file_exists("images/".$random.".".$extension))
			$random = substr(md5(rand()),0,7);
		
		move_uploaded_file($_FILES["file"]["tmp_name"], "images/".$random.".".$extension);
		$id = $_SESSION["id"];
		$query = "UPDATE users SET image='{$random}.{$extension}' WHERE ID='{$id}'";
		$_SESSION["image"] = $random.".".$extension;
		mysql_query($query) or die(mysql_error());
		header("Location: account.php");
    }
}
else
{
	echo "Invalid file";
}
?>