<?php

function get() {
	$image1 = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . '/storage/bd1483eb4f4b9c8d38b7965eba6d9b9be958ed4fc06568eed38a2c0e63686ba8.png');
	$image2 = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . '/storage/moustache.png');
	imagecopy($image1, $image2, imagesx($image1) / 2.5, imagesy($image1) / 2, imagesx($image1) / 2.5, imagesy($image1) / 2, 156, 130);
	header('Content-Type: image/png');
	imagepng($image1);
	imagedestroy($image1);
}
