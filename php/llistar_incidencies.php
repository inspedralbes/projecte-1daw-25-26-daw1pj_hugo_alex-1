<?php
$mysqli = include_once "conexion.php";
$resultado = $mysqli->query("SELECT id, nombre, descripcion, genere FROM v");
$videojuegos = $resultado->fetch_all(MYSQLI_ASSOC);
include_once "header.php"
?>








<?php
include_once "fotter.php"
?>