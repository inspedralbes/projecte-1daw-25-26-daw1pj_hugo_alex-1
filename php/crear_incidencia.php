<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include_once "connexio.php";

$idTipus = $_POST["idTipus"];
$idDepartaments = $_POST["idDepartament"];
$descripcio = $_POST["descripcio"];

$sentencia = $conn->prepare("INSERT INTO INCIDENCIA (idTipo, idDepartamento, fechaInicio, descripcion) VALUES(?, ?, NOW() ,?)");
$sentencia->bind_param("iis", $idTipus, $idDepartaments, $descripcio);
$sentencia->execute();
$idNova = $conn->insert_id;
?>
<?php include_once "header.php"; ?>
<div class="container text-center mt-5">
    <div class="alert alert-success">
        <h4>Incidència creada correctament!</h4>
        <p>El teu número d'incidència és: <strong><?= $idNova ?></strong></p>
        <p>Guarda aquest número per revisar l'estat de la teva incidència.</p>
    </div>
    <a href="llistar_incidencies_usuari.php" class="btn btn-primary">Veure totes les incidències</a>
</div>
<?php include_once "fotter.php"; ?>