<?php

function resize($img, $to, $width = 0, $height = 0)
{
	$dimensions = getimagesize($img);
	$ratio      = $dimensions[0] / $dimensions[1];
 
	// Calcul des dimensions si 0 passé en paramètre
	if($width == 0 && $height == 0){
		$width = $dimensions[0];
		$height = $dimensions[1];
	}elseif($height == 0){
		$height = round($width / $ratio);
	}elseif ($width == 0){
		$width = round($height * $ratio);
	}
 
	if($dimensions[0] > ($width / $height) * $dimensions[1]){
		$dimY = $height;
		$dimX = round($height * $dimensions[0] / $dimensions[1]);
		$decalX = ($dimX - $width) / 2;
		$decalY = 0;
	}
	if($dimensions[0] < ($width / $height) * $dimensions[1]){
		$dimX = $width;
		$dimY = round($width * $dimensions[1] / $dimensions[0]);
		$decalY = ($dimY - $height) / 2;
		$decalX = 0;
	}
	if($dimensions[0] == ($width / $height) * $dimensions[1]){
		$dimX = $width;
		$dimY = $height;
		$decalX = 0;
		$decalY = 0;
	}
 
	// Création de l'image avec la librairie GD        
    $pattern = imagecreatetruecolor($width, $height);
	$type = mime_content_type($img);
	switch (substr($type, 6)) {
		case 'jpeg':
			$image = imagecreatefromjpeg($img);
			break;
		case 'gif':
			$image = imagecreatefromgif($img);
		    break;
		case 'png':
			$image = imagecreatefrompng($img);
			break;
	}
	imagecopyresampled($pattern, $image, 0, 0, 0, 0, $dimX, $dimY, $dimensions[0], $dimensions[1]);
	imagedestroy($image);
	imagejpeg($pattern, $to, 100);
 
	return TRUE;
}
