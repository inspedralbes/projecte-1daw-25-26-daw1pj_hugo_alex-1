<?php
require 'vendor/autoload.php';

$database = getenv('MONGO_DB') ?: 'demo';

if (getenv('MONGODBURI')) {
    $uri = getenv('MONGODBURI');
} else {
    // Hestia
    $uri = "mongodb+srv://a25hugberbat_db_user:S6nxleFAzEmk0hNB@cluster0.vzpcyjk.mongodb.net/?appName=Cluster0";
}

$client = new MongoDB\Client($uri);
$db = $client->$database;
return $db;