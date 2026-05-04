<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include_once "connexio.php";

$idTipus = $_POST["idTipus"];
$idDepartaments = $_POST["idDepartament"];
$descripcio = $_POST["descripcio"];

$sentencia = $conn->prepare("INSERT INTO INCIDENCIA (idTipo, idDepartamento, fechaInicio, descripcion) VALUES(?, ?, NOW() ,?)");
$sentencia->bind_param("iis", $idTipus, $idDepartaments, $descripcio);
$sentencia->execute();
header("Location: llistar_incidencies_usuari.php");
?>