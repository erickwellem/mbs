<?php


	// The file
	$filename = $strFilePath;


	// Set a maximum height and width
	$width = 100;
	$height = 100;

	// Content type
	header('Content-Type: image/' . $_REQUEST['file']);

	$im = new imagick($filename . '[0]');
	$im->setImageFormat("png");
	
	if (intval($_REQUEST['w']) > 0)
	{
		$im->thumbnailImage(intval($_REQUEST['w']), null);
	}
	
	else
	{
		$im->thumbnailImage($width, null);
	}
	
	header("Content-Type: image/png");
	
	echo $im;

?>