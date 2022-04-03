<?php
session_start();
$_SESSION['logged_in'] = FALSE;
require_once('config.php');

$login_button = '';


if (isset($_GET["code"])) {

    $token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);
} else {
    header('Location: ../login.php');
}

// echo '<pre>';
// echo var_dump($userdata);
// echo '</pre>';


if (!isset($token['error'])) {

    $client->setAccessToken($token['access_token']);


    $_SESSION['access_token'] = $token['access_token'];

    $oauth = new Google_Service_Oauth2($client);
    $userdata = $oauth->userinfo->get();

    // $data = $google_service->userinfo->get();


    if (!empty($userdata['given_name'])) {
        $_SESSION['user_first_name'] = $userdata['given_name'];
    }

    if (!empty($userdata['family_name'])) {
        $_SESSION['user_last_name'] = $userdata['family_name'];
    }

    if (!empty($userdata['email'])) {
        $_SESSION['user_email_address'] = $userdata['email'];
    }

    if (!empty($userdata['gender'])) {
        $_SESSION['user_gender'] = $userdata['gender'];
    }

    if (!empty($userdata['picture'])) {
        $_SESSION['user_image'] = $userdata['picture'];
    }
    
}


if (!isset($_SESSION['access_token'])) {

    $login_button = '<a href="' . $google_client->createAuthUrl() . '">Login With Google</a>';
} else {
    header('Location:passwordSetup.php');
    
}
?>
