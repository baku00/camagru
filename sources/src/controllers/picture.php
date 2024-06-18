<?php

function get() {
	$image1 = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . '/storage/825761a78804917c95cc566d6f1dc02be5c605d748a54e84675a4f294deaac3e.png');
	$image2 = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . '/storage/moustache.png');
	imagecopy($image1, $image2, 557, 407, 0, 0, imagesx($image2), imagesy($image2));
	header('Content-Type: image/png');
	imagepng($image1);
	imagedestroy($image1);
}
