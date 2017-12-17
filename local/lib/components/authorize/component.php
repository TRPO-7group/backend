<?php

/**
 * Example of retrieving an authentication token of the Google service
 *
 * PHP version 5.4
 *
 * @author     David Desberg <david@daviddesberg.com>
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2012 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

use OAuth\OAuth2\Service\Google;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;
session_start();
/**
 * Bootstrap the example
 */
require_once __DIR__ . '/bootstrap.php';

// Session storage
$storage = new Session();

// Setup the credentials for the requests
$credentialsGoogle = new Credentials(
    $servicesCredentials['google']['key'],
    $servicesCredentials['google']['secret'],
   "http://reposit-catalog.tk/reposit-catalog/?auth_from=google"
);

$credentialsGitHub = new Credentials(
    $servicesCredentials['github']['key'],
    $servicesCredentials['github']['secret'],
    "http://reposit-catalog.tk/reposit-catalog/?auth_from=github"
);



// Instantiate the Google service using the credentials, http client and storage mechanism for the token
/** @var $googleService Google */
$googleService = $serviceFactory->createService('google', $credentialsGoogle, $storage, array('userinfo_email', 'userinfo_profile'));
$gitHub = $serviceFactory->createService('GitHub', $credentialsGitHub, $storage, array('user'));

if ($_GET["logout"] == "Y")
{
    unset($_SESSION["auth_info"]);
}
if (!$_SESSION["auth_info"]) {
    if (!empty($_GET['code'])) {
        // retrieve the CSRF state parameter
        $state = isset($_GET['state']) ? $_GET['state'] : null;

        if($_GET["auth_from"] == "google") {
            // This was a callback request from google, get the token
            $googleService->requestAccessToken($_GET['code'], $state);

            // Send a request with it
            $result = json_decode($googleService->request('userinfo'), true);
            $resArr = $result;
        }
        if ($_GET["auth_from"] == "github")
        {
            $gitHub->requestAccessToken($_GET['code']);

            $result = json_decode($gitHub->request('user'), true);
            $resArr = array(
                "id" => $result["id"],
                "name" => $result["login"],
                "picture" => $result["avatar_url"],
                "email" => $result["email"]
            );
        }

        // Show some of the resultant data


        $users = DB::getList("user","*", false,"google_id=" . $resArr["id"]);

        if (!count($users))
        {
            DB::insertRow("user",
                array('user_mail', 'user_type','name','preview_img','google_id'),
                array($resArr['email'], '0', $resArr['name'], $resArr['picture'], $resArr['id']));
            $users = DB::getList("user","*", false,"google_id=" . $resArr["id"]);
        }
        $arResult["result"] = $users[0];
        $_SESSION["auth_info"]  = $arResult["result"];
        //echo 'Your unique google user id is: ' . $result['id'] . ' and your name is ' . $result['name'];

    } elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
        if($_GET["auth_from"] == "google")
            $url = $googleService->getAuthorizationUri() . "&auth_from=google";
        if($_GET["auth_from"] == "github")
            $url = $gitHub->getAuthorizationUri() . "&auth_from=github";
        header('Location: ' . $url);
    } else {
        $url = $currentUri->getRelativeUri() . '?' . $_SERVER['QUERY_STRING'] . '&go=go';
        $arResult["auth_url"] = $url;
        // echo "<a href='$url'>Login with Google!</a>";
    }
} else
{
    $arResult["result"] = $_SESSION["auth_info"];
}

require $template. ".php";
