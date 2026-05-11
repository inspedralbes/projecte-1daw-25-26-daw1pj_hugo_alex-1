<?php
$enDocker = (gethostbyname('db') !== 'db');

if ($enDocker) {
    $servername = "db";
    $username = getenv('VAR1');
    $password = getenv('VAR2');
    $dbname = "indidenciesP";
} else {
    $servername = "localhost";
    $username = "a25hugberbat_incidencies";
    $password = "Plataforma_Incidencies1234";
    $dbname = "a25hugberbat_incidencies";
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de connexió: " . $conn->connect_error);
}
?>