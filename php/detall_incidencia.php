<?php
include_once "header.php";
include_once "connexio.php";
?>
<?php
$id = $_GET['idBusca'] ?? null;
$sql = "SELECT 
    i.idIncidencia,
    i.descripcion,
    DATE_FORMAT(i.fechaInicio, '%d/%m/%Y') AS fechaInicio,
    DATE_FORMAT(i.fechaFin, '%d/%m/%Y') AS fechaFin,
    t.nombre AS tecnico,
    d.nombre AS departamento,
    tp.nombre AS tipo
FROM INCIDENCIA i
LEFT JOIN TECNICO t ON i.idTecnico = t.idTecnico
LEFT JOIN DEPARTAMENTO d ON i.idDepartamento = d.idDepartamento
LEFT JOIN TIPO tp ON i.idTipo = tp.idTipo
WHERE i.idIncidencia = ?";

$sentencia = $conn->prepare($sql);
$sentencia->bind_param("i", $id);
$sentencia->execute();
$result = $sentencia->GET_result();
$inc = $result->fetch_assoc();
?>
<div class="container">
    <h2 class="mb-4">Detall Incidència</h2>
    <?php if ($inc): ?>
        <div class="card shadow-sm">
            <div class= "card-body">
                <h3 class ="cart-title">INCIDÈNCIA #<?= $inc['idIncidencia'] ?></h3>
                <hr>
                <p><strong>Tipus:</strong> <?= $inc['tipo'] ?? '-' ?></p>
                <p><strong>Departament:</strong> <?= $inc['departamento'] ?? '-' ?></p>
                <p><strong>Tècnic:</strong> <?= $inc['tecnico'] ?? 'Sense assignar' ?></p>
                <p><strong>Data Inici:</strong> <?= $inc['fechaInicio'] ?></p>
                <p><strong>Data Fi:</strong> <?= $inc['fechaFin'] ?? 'Oberta' ?></p>
                <p><strong>Descripció:</strong> <?= $inc['descripcion'] ?></p>

            </div>
        </div>
        <?php else: ?>
            <div class="alert alert-danger">No s'ha trobat cap incidència amb aquert número</div>
        <?php endif; ?>

</div>

<?php
include_once "fotter.php";
?>