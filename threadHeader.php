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

$id = mysql_real_escape_string($_GET["id"]);

$query = "SELECT * FROM threads, users AS author WHERE threads.authorID=author.ID AND threads.ID={$id}";
$result = mysql_query($query) or die(mysql_error());
$image = mysql_result($result,0,"author.image");
$email = mysql_result($result,0,"author.email");
if($image == "gravatar")
	$image = "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode("http://alvasb.com/images/default.png")."&s=50";
else
	$image = "image.php?image=images/{$image}&width=50&height=50";
date_default_timezone_set('EST');
?>
<h2><?php echo mysql_result($result,0,"threads.subject"); ?></h2>
<p class="author"><img src="<?php echo $image; ?>" />Posted by <?php echo mysql_result($result,0,"author.name"); ?></p>
<p class="author"><?php echo date("F j, Y, g:i a",mysql_result($result,0,"threads.time")); ?></p>
