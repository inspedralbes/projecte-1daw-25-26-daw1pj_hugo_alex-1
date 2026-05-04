<?php
include_once "connexio.php";

$sql = "
    SELECT 
        i.idIncidencia,
        i.descripcion,
        i.prioritat,
        DATE_FORMAT(i.fechaInicio, '%d/%m/%Y') AS fechaInicio,
        DATE_FORMAT(i.fechaFin, '%d/%m/%Y') AS fechaFin,
        t.nombre AS tecnico,
        d.nombre AS departamento,
        tp.nombre AS tipo
    FROM INCIDENCIA i
    LEFT JOIN TECNICO t ON i.idTecnico = t.idTecnico
    LEFT JOIN DEPARTAMENTO d ON i.idDepartamento = d.idDepartamento
    LEFT JOIN TIPO tp ON i.idTipo = tp.idTipo
    ORDER BY i.idIncidencia
";

$result = $conn->query($sql);
?>

<?php include_once "header.php"; ?>

<div class="container">
    <h2 class="mb-4">Llistat d'Incidències</h2>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">No hi ha incidències registrades.</div>
    <?php else: ?>
        <div class="table-responsive">
            <small>
                <table class="table table-striped table-hover table-sm">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th class="d-none d-md-table-cell">Tipus</th>
                            <th class="d-none d-md-table-cell">Departament</th>
                            <th>Tècnic</th>
                            <th>Data Inici</th>
                            <th class="d-none d-md-table-cell">Data Fi</th>
                            <th>Descripció</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($inc = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $inc['idIncidencia'] ?></td>
                            <td class="d-none d-md-table-cell"><?= $inc['tipo'] ?? '-' ?></td>
                            <td class="d-none d-md-table-cell"><?= $inc['departamento'] ?? '-' ?></td>
                            <td><?= $inc['tecnico'] ?? 'Sense assignar' ?></td>
                            <td><?= $inc['fechaInicio'] ?></td>
                            <td class="d-none d-md-table-cell"><?= $inc['fechaFin'] ?? 'Oberta' ?></td>
                            <td><?= $inc['descripcion'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </small>
        </div>
    <?php endif; ?>
</div>

<?php include_once "fotter.php"; ?>