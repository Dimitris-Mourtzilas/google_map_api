<?php


require('../../vendor/autoload.php');
require('db.php');

const CLIENT_ID = '242508879995-4qurnt7na634acq0mohv9kagp09d8htq.apps.googleusercontent.com';
const CLIENT_SECRET = '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';
const REDIRECT_URI = 'http://localhost/api/login.php';


$client = new Google_Client();
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);

# redirection location is the path to login.php
$client->setRedirectUri(REDIRECT_URI);
$client->addScope("email");
$client->addScope("profile");
