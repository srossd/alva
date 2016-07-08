<?php
header('Content-Type: image/png');
function resize_image_crop($image, $width, $height)
 {

	$w = @imagesx($image); //current width

	$h = @imagesy($image); //current height
	if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.'; return false; }
	if (($w == $width) && ($h == $height)) { return $image; }  //no resizing needed
	$ratio = $width / $w;       //try max width first...
	$new_w = $width;
	$new_h = $h * $ratio;    
	if ($new_h < $height) {  //if that created an image smaller than what we wanted, try the other way
		$ratio = $height / $h;
		$new_h = $height;
		$new_w = $w * $ratio;
	}
	$image2 = imagecreatetruecolor ($new_w, $new_h);
	imagecopyresampled($image2,$image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);    
	if (($new_h != $height) || ($new_w != $width)) {    //check to see if cropping needs to happen
		$image3 = imagecreatetruecolor ($width, $height);
		if ($new_h > $height) { //crop vertically
			$extra = $new_h - $height;
			$x = 0; //source x
			$y = round($extra / 2); //source y
			imagecopyresampled($image3,$image2, 0, 0, $x, $y, $width, $height, $width, $height);
		} else {
			$extra = $new_w - $width;
			$x = round($extra / 2); //source x
			$y = 0; //source y
			imagecopyresampled($image3,$image2, 0, 0, $x, $y, $width, $height, $width, $height);
		}
		imagedestroy($image2);
		return $image3;
	} else {
		return $image2;
	}
}
$imagepath = $_GET["image"];
$height = $_GET["height"];
$width = $_GET["width"];

$filetype = substr($imagepath,-3);
if($filetype == "png")
	$image = imagecreatefrompng($imagepath);
if($filetype == "jpg" || $filetype == "peg")
	$image = imagecreatefromjpeg($imagepath);
if($filetype == "gif")
	$image = imagecreatefromgif($imagepath);
	
$image = resize_image_crop($image, $width, $height);
imagepng($image);
?>