<?php
if (getenv('VAR1')) {
    // Estamos en Docker (local)
    $servername = "db";
    $username = getenv('VAR1');
    $password = getenv('VAR2');
    $dbname = "indidenciesP";
} else {
    // Estamos en Hestia (servidor real)
    $servername = "localhost"; 
    $username = "a25hugberbat_incidencies";    
    $password = "Plataforma_Incidencies1234";        
    $dbname = "a25hugberbat_incidencies";
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "<p>Error de connexió: " . htmlspecialchars($conn->connect_error) . "</p>";
    die("Error de connexió: " . $conn->connect_error);
}
?>