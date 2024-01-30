<?php
require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');

(Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']))->load();

define('ROUTE_URL_INDEX', rtrim($_ENV['AUTH0_BASE_URL'], '/'));
define('ROUTE_URL_LOGIN', 'https://wardrobe.nicholewagner.com/login.php');
define('ROUTE_URL_CALLBACK','https://wardrobe.nicholewagner.com/callback.php');
define('ROUTE_URL_LOGOUT', 'https://wardrobe.nicholewagner.com/logout');

$auth0 = new \Auth0\SDK\Auth0([
	'domain' => $_ENV['AUTH0_DOMAIN'],
	'clientId' => $_ENV['AUTH0_CLIENT_ID'],
	'clientSecret' => $_ENV['AUTH0_CLIENT_SECRET'],
	'cookieSecret' => $_ENV['AUTH0_COOKIE_SECRET']
]);

	  
	  ?>
