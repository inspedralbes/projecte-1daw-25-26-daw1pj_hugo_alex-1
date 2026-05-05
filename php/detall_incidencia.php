<?php
include_once "header.php";
include_once "connexio.php";

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
$result = $sentencia->get_result();
$inc = $result->fetch_assoc();
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-8 mx-auto">
            <h2 class="mb-4 text-center">Detall de l'Incidència</h2>

            <?php if ($inc): ?>
                <div class="card border-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Incidència #<?= $inc['idIncidencia'] ?></h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Tipus:</strong> <?= $inc['tipo'] ?? 'N/A' ?></li>
                            <li class="list-group-item"><strong>Departament:</strong> <?= $inc['departamento'] ?></li>
                            <li class="list-group-item"><strong>Tècnic:</strong> <?= $inc['tecnico'] ?? 'No assignat' ?></li>
                            <li class="list-group-item"><strong>Data d'Inici:</strong> <?= $inc['fechaInicio'] ?></li>
                            <li class="list-group-item"><strong>Data de Fi:</strong> <?= $inc['fechaFin'] ?? 'Encara oberta' ?></li>
                        </ul>

                        <div class="mt-4 p-3 border rounded bg-light">
                            <strong>Descripció:</strong>
                            <p class="mt-2"><?= $inc['descripcion'] ?></p>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="index.php" class="btn btn-secondary">Tornar al llistat</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger shadow-sm">
                    <strong>Error:</strong> No s'ha trobat cap incidència con el número <strong><?= htmlspecialchars($id) ?></strong>.
                </div>
                <div class="text-center">
                    <a href="index.php" class="btn btn-primary">Tornar enrere</a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php
include_once "fotter.php";
?>