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

$sqlActuacions = "SELECT comentario, tiempo, DATE_FORMAT(fechaAccion, '%d/%m/%Y %H:%i') AS fechaAccion FROM ACCION WHERE idIncidencia = ? AND visible = 1";
$sentenciaActuacions = $conn->prepare($sqlActuacions);
$sentenciaActuacions->bind_param("i", $id);
$sentenciaActuacions->execute();
$resultActuacions = $sentenciaActuacions->get_result();
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
                        <div class="mt-4">
                            <h5>Actuacions:</h5>
                                <?php if ($resultActuacions->num_rows === 0): ?>
                                <p class="text-muted">No hi ha actuacions visibles.</p>
                                <?php else: ?>
                                <?php while ($act = $resultActuacions->fetch_assoc()): ?>
                                    <div class="p-3 border rounded bg-light mb-2">
                                        <small class="text-muted"><?= $act['fechaAccion'] ?> | Temps: <?= $act['tiempo'] ?></small>
                                            <p class="mt-1 mb-0"><?= htmlspecialchars($act['comentario']) ?></p>
                                        </div>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer text-center p-4">
                        <a href="llistar_incidencies_usuari.php" class="btn btn-outline-primary mx-auto p2"> <i class="fa-solid fa-arrow-left"></i> Tornar al llistat d'inciencies</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger shadow-sm">
                    <strong>Error:</strong> No s'ha trobat cap incidència con el número <strong><?= htmlspecialchars($id) ?></strong>.
                </div>
                <div class="text-center">
                    <a href="index.php" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i> Tornar enrere</a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php
include_once "fotter.php";
?>