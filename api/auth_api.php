<?php

require($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
(Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']))->load();

define('ROUTE_URL_INDEX', rtrim($_ENV['AUTH0_BASE_URL'], '/'));
define('ROUTE_URL_LOGIN', 'https://gear.nicholewagner.com/login.php');
define('ROUTE_URL_CALLBACK', 'https://gear.nicholewagner.com/callback.php');
define('ROUTE_URL_LOGOUT', 'https://gear.nicholewagner.com/logout');

function getAccessToken()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://dev-b1lx2578pk5fbf0h.us.auth0.com/oauth/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            "client_id" => $_ENV['AUTH0_API_CLIENT_ID'],
            "client_secret" => $_ENV['AUTH0_API_CLIENT_SECRET'],
            "audience" => "https://dev-b1lx2578pk5fbf0h.us.auth0.com/api/v2/",
            "grant_type" => "client_credentials"
        ]),
        CURLOPT_HTTPHEADER => array(
            "content-type: application/json"
        ),
    ));

    $responseAPI = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $responseAPI = json_decode($responseAPI, true);

        $accessToken = $responseAPI['access_token'] ?? null;
        return $accessToken;
    }
}
function getUserRole($userId)
{
    $accessToken = getAccessToken();
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => "https://dev-b1lx2578pk5fbf0h.us.auth0.com/api/v2/users/".$userId."/roles",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "authorization: Bearer ".$accessToken
      ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $response = json_decode($response, true);
        //print_r($response);

        if (isIdInArray($response, 'rol_cd0E709R2wH2lP4v')) {
            return true;
        } else {
            return false;
        }
    }
}
function isIdInArray($array, $searchId)
{
    foreach ($array as $item) {
        if (isset($item['id']) && $item['id'] === $searchId) {
            return true;
        }
    }
    return false;
}
