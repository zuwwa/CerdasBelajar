<?php

//Include Google Client Library for PHP autoload file
#untuk konfigurasi via composer
require_once 'google-api/vendor/autoload.php';

#untuk konfigurasi via download manual
#require_once 'google-api-php-client/vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('100192998042-g52c7kkkvp88gaduk65akb6tcob4sc13.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('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');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/CerdasBelajar/index.php');

//
$google_client->addScope('email'); 
$google_client->addScope('profile');

?>