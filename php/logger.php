<?php
require 'vendor/autoload.php';

$db = require 'connexion_mongo.php';
$collection = $db->logs;


$client = new MongoDB\Client("mongodb://root:example@mongo:27017");
$collection = $client->demo->logs;

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
$documents = $collection->find();

foreach ($documents as $doc) {
    echo "<p>";
    $ts = $doc['timestamp']->toDateTime()->format('Y-m-d H:i:s');
    echo htmlspecialchars($ts);
    echo " [" . htmlspecialchars($doc['method'] ?? 'x') . "] ";
    echo htmlspecialchars($doc['url'] ?? 'x');
    echo " — IP: " . htmlspecialchars($doc['ip'] ?? 'x');
    echo " — Usuari: " . htmlspecialchars($doc['usuari'] ?? 'anònim');
    echo " — Navegador: " . htmlspecialchars($doc['navegador'] ?? 'x');
    echo "</p>";
}
