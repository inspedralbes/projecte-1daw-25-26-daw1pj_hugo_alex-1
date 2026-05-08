<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include_once "connexio.php";

$idIncidencia = $_POST["idIncidencia"];
$comentario = $_POST["comentario"];
$temps = $_POST["temps"] . ":00";
$visible = isset($_POST["visible"]) ? 1 : 0;

$sentencia = $conn->prepare("INSERT INTO ACCION (idIncidencia, comentario, tiempo, fechaAccion, visible) VALUES(?, ?, ?, NOW(), ?)");
$sentencia->bind_param("issi", $idIncidencia, $comentario, $temps, $visible);
$sentencia->execute();
?>
<?php include_once "header.php"; ?>
<div class="container text-center mt-5">
    <div class="alert alert-success">
        <h4>Actuació registrada correctament!</h4>
        <p>Has afegit una nova actuació a la incidència #<?= htmlspecialchars($idIncidencia) ?>.</p>
    </div>
    <a href="historial_actuacions.php" class="btn btn-primary">Veure totes les actuacions</a>
</div>
<?php include_once "fotter.php"; ?>
