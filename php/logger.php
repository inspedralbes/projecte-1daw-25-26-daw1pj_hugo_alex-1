<?php
require 'vendor/autoload.php';

$db = require 'connexion_mongo.php';
$collection = $db->logs;

// Obtenim l'adreça IP origen de la petció.
// Teniu informació sobre l'operador ?? a 
// https://phpsensei.es/operadores-en-php-null-coalesce-operator/
// "Si no es pot obtenir, es fa servir 'unknown' com a valor per defecte"

$ip        = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$url       = $_SERVER['REQUEST_URI'] ?? 'unknown';
$metode    = $_SERVER['REQUEST_METHOD'] ?? 'unknown';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$timestamp = new MongoDB\BSON\UTCDateTime();


$collection->insertOne([
    'url'        => $url,
    'method'     => $metode,
    'timestamp'  => $timestamp,
    'navegador'  => $userAgent,
    'ip'         => $ip,
]);

$documents = $collection->find([], ['sort' => ['timestamp' => -1]]);


// Obtenir tots els documents de la col·lecció users de la BBDD demo
// $collection = $client->demo->users; #no cal, ja que ho hem fet abans