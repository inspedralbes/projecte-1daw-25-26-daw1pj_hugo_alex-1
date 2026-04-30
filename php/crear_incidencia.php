<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
$mysqli = include_once "conexion.php";

$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$genere = $_POST["genere"];

$sentencia = $mysqli->prepare("INSERT INTO videojuegos
(nombre, descripcion, genere)
VALUES
(?, ?, ?)");

$sentencia->bind_param("sss", $nombre, $descripcion, $genere);
$sentencia->execute();
header("Location: listar.php");