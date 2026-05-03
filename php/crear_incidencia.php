<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include_once "connexio.php";

$idTipus = $_POST["idTipus"];
$idDepartaments = $_POST["idDepartament"];

$sentencia = $conn->prepare("INSERT INTO INCIDENCIA (idTipo, idDepartamento, fechaInicio) VALUES(?, ?, NOW())");
$sentencia->bind_param("ii", $idTipus, $idDepartaments);
$sentencia->execute();
header("Location: llistar_incidencies.php");
?>