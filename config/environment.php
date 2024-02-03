<?php

require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');

if ($_SERVER['HTTP_HOST'] === 'gearcheck.localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
	$env = 'local';
	$proxy = "http://";

}
else {
	$env = "prod";
	$proxy = "https://";
}

$dotenv = \Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/config/','.env.'.$env);
$dotenv->load();