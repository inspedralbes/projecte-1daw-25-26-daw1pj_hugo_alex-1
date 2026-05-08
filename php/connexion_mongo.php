<?php
require 'vendor/autoload.php';

$host     = getenv('MONGO_HOST')     ?: 'mongo';
$port     = getenv('MONGO_PORT')     ?: '27017';
$user     = getenv('MONGO_USER')     ?: 'root';
$password = getenv('MONGO_PASSWORD') ?: 'example';
$database = getenv('MONGO_DB')       ?: 'demo';

$uri = "mongodb://{$user}:{$password}@{$host}:{$port}";

$client = new MongoDB\Client($uri);
$db = $client->$database;

return $db;