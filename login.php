<?php

require($_SERVER['DOCUMENT_ROOT'].'/config/environment.php');
require($_SERVER['DOCUMENT_ROOT'].'/api/auth.php');


define('ROUTE_URL_INDEX', rtrim($_ENV['AUTH0_BASE_URL'], '/'));
define('ROUTE_URL_LOGIN', 'https://gear.nicholewagner.com/login.php');
define('ROUTE_URL_CALLBACK', 'https://gear.nicholewagner.com/callback.php');
define('ROUTE_URL_LOGOUT', 'https://gear.nicholewagner.com/logout');
$auth0 = new \Auth0\SDK\Auth0([
    'domain' => $_ENV['AUTH0_DOMAIN'],
    'clientId' => $_ENV['AUTH0_CLIENT_ID'],
    'clientSecret' => $_ENV['AUTH0_CLIENT_SECRET'],
    'cookieSecret' => $_ENV['AUTH0_COOKIE_SECRET']
]);

header("Location: " . $auth0->login(ROUTE_URL_CALLBACK));
