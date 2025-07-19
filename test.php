<?php
require_once 'google-api/vendor/autoload.php';

if (class_exists('Google_Service_Oauth2')) {
    echo "✅ Google API Client terdeteksi!";
} else {
    echo "❌ Google API Client TIDAK ditemukan!";
}
