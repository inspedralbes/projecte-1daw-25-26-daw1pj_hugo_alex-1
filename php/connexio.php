<?php
$servername = "db"; 
$username = getenv('VAR1'); 
$password = getenv('VAR2'); 
$dbname = "indidenciesP"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "<p>Error de connexió: " . htmlspecialchars($conn->connect_error) . "</p>";
    die("Error de connexió: " . $conn->connect_error);
}
?>