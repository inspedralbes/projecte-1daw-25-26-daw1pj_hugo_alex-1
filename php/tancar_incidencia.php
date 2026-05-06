<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include_once "connexio.php";

$idIncidencia = $_POST["idIncidencia"];

$sentencia = $conn->prepare("UPDATE INCIDENCIA SET fechaFin = NOW() WHERE idIncidencia = ?");
$sentencia->bind_param("i", $idIncidencia);
$sentencia->execute();
?>
<?php include_once "header.php"; ?>
<div class="container text-center mt-5">
    <div class="alert alert-success">
        <h4>Incidència tancada correctament!</h4>
    </div>
</div>
<?php include_once "fotter.php"; ?>








