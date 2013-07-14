<?php
/**************************************************************************************************
 * EW Project Management System Configuration File
 * @Author		: Erick Wellem (me@erickwellem.com)
 * 				  October, 2009
 * 		
 * Desc: Configuration file. This version was made and configured for 
 * P4L MBS (Marketing Booking System) on February - June 2013
 **************************************************************************************************/

// Database settings
$DB_HOST = 'localhost';			// usually 'localhost', if you use a separated database server please fill in with the ip or hostname
$DB_USER = 'p4lmbs';			// mysql database username
$DB_PASS = 'p4lmbs';			// mysql database password
$DB_NAME = 'p4lmbs';			// mysql database name

// timezone
date_default_timezone_set('Asia/Jakarta');

// Path
$STR_PATH = $_SERVER['DOCUMENT_ROOT'] . '/www/pharmacy4less.com.au/mbs/'; // with trailing slashes
$STR_URL = 'http://' . $_SERVER['HTTP_HOST'] . '/www/pharmacy4less.com.au/mbs/'; // with trailing slashes

// ImageMagick's convert path
$STR_IMAGE_MAGICK_CONVERT_PATH = '/usr/bin/convert'; // for linux
$STR_IMAGE_MAGICK_CONVERT_PATH_WIN = '"C:\\Program Files\\ImageMagick-6.7.1-Q16\\convert.exe"'; // for windows 
#$STR_IMAGE_MAGICK_CONVERT_PATH_WIN = 'C:\\Program Files\\Image Magick\\convert.exe'; // for windows

// record tables
$TABLE_MAX_ROW_PER_PAGE = 30;

// php reporting
error_reporting(E_ERROR);
#error_reporting(E_ALL);

// php memory limit
ini_set('memory_limit','512M');

?>