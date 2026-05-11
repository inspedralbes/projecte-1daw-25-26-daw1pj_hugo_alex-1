<?php
include_once "header.php";
include_once "connexio.php";

$tecnicVolver = $_GET['tecnic'] ?? '';

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

                        <div class="card-footer d-flex flex-column gap-2">
                            <div class="d-flex gap-2">
                                <a href="llistar_incidencies_tecnic.php?tecnic=<?= urlencode($tecnicVolver) ?>" class="btn btn-secondary flex-fill text-center">
                                    <i class="fas fa-arrow-left"></i> Tornar a la llista
                                </a>
                                <a href="afegir_actuacio.php?idIncidencia=<?= $inc['idIncidencia'] ?>" class="btn btn-secondary flex-fill text-center"><i class="fa-solid fa-plus"></i> Afegir Actuació</a>
                            </div>
                            <a href="historial_actuacions.php" class="btn btn-primary btn-lg w-100 shadow"><i class="fa-solid fa-clock"></i> Veure l'historial de les actuacions</a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger shadow-sm">
                            <strong>Error:</strong> No s'ha trobat cap incidència con el número <strong><?= htmlspecialchars($id) ?></strong>.
                        </div>
                        <div class="text-center">
                            <a href="llistar_incidencies_tecnic.php?tecnic=<?= urlencode($tecnicVolver) ?>" class="btn btn-secondary mb-3">
                                <i class="fa-solid fa-arrow-left"></i> Tornar a la llista de <?= htmlspecialchars($tecnicVolver) ?>
                            </a>
                        </div>

                    <?php endif; ?>

                    </div>
                </div>
        </div>

        <?php
        include_once "fotter.php";
        ?>