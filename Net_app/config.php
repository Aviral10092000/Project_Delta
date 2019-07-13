<?php
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'aviral');
	define('DB_PASSWORD', '123');
	define('DB_NAME','netapp');

$mysqli = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

if($mysqli === false)
{
	die("ERROR CONNECTING TO MySQL".$mysqli->connect_error);
}

?>