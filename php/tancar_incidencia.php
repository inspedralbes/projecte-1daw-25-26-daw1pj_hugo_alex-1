<?php
$tecnicVolver = $_POST['tecnic'] ?? '';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once "connexio.php";

$idIncidencia = $_POST["idIncidencia"];
$accio = $_POST["accio"] ?? 'tancar'; //tancar o obrir

$sentencia = $conn->prepare(
    $accio === 'obrir'
        ? "UPDATE INCIDENCIA SET fechaFin = NULL WHERE idIncidencia = ?"
        : "UPDATE INCIDENCIA SET fechaFin = NOW() WHERE idIncidencia = ?"
);
$sentencia->bind_param("i", $idIncidencia);
$sentencia->execute();
?>
<?php include_once "header.php"; ?>
<div class="container text-center mt-5">
    <div class="alert <?= $accio === 'obrir' ? 'alert-warning' : 'alert-success' ?>">
        <h4>Incidència <?= $accio === 'obrir' ? 'oberta' : 'tancada' ?> correctament!</h4>
    </div>
    <a href="llistar_incidencies_tecnic.php?tecnic=<?= urlencode($tecnicVolver) ?>" class="btn btn-primary flex-fill text-center">
        <i class="fas fa-arrow-left"></i> Tornar a la llista
    </a>
</div>
<?php include_once "fotter.php"; ?>