<?php
include_once "connexio.php";

// SQL optimizado para mostrar solo incidencias abiertas (fechaFin IS NULL)
$sql = "
    SELECT
        t.idTecnico,
        t.nombre AS nomTecnic,
        i.prioritat,
        i.idIncidencia,
        i.descripcion AS descripcioIncidencia,
        DATE_FORMAT(i.fechaInicio, '%d/%m/%Y') AS dataInici,
        IFNULL(SUM(TIME_TO_SEC(a.tiempo)), 0) AS tempsTotalDedicat
    FROM TECNICO t
    INNER JOIN INCIDENCIA i ON t.idTecnico = i.idTecnico
    LEFT JOIN ACCION a ON i.idIncidencia = a.idIncidencia
    WHERE i.fechaFin IS NULL
    GROUP BY
        t.idTecnico,
        t.nombre,
        i.prioritat,
        i.idIncidencia,
        i.descripcion,
        i.fechaInicio
    ORDER BY t.nombre ASC, i.idIncidencia DESC;
";

$result = $conn->query($sql);

$capçaleres = [
    ['Tècnic',           ''],
    ['ID',                   ''],
    ['Prioritat',           ''],
    ['Data Inici',          ''],
    ['Temps Dedicat',       ''],
    ['Descripció',          ''],
];
?>

<?php include_once "header.php"; ?>

<div class="container px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Informe de Tècnics</h2>
        <a href="admin.php" class="btn btn-outline-primary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Tornar
        </a>
    </div>

    <div id="alertDescripcio" class="alert alert-primary d-none alert-dismissible fade show mt-2" role="alert">
        <strong>Detall de la descripció:</strong>
        <div id="alertText" class="mt-1"></div>
        <button type="button" class="btn-close" onclick="this.parentElement.classList.add('d-none')"></button>
    </div>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">No hi ha incidències obertes assignades a cap tècnic.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle" style="font-size: 0.72em; min-width: 1000px;">
                <thead>
                    <tr>
                        <?php foreach ($capçaleres as [$label, $classes]): ?>
                            <th class="<?= $classes ?> bg-primary text-white p-2 border-primary">
                                <?= $label ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($inc = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?= htmlspecialchars($inc['nomTecnic']) ?></td>
                            <td class="fw-bold">#<?= $inc['idIncidencia'] ?></td>
                            <td>
                                <?php
                                $color = match($inc['prioritat']) {
                                    'Alta'  => 'danger',
                                    'Mitja' => 'warning',
                                    'Baixa' => 'success',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $color ?> <?= $color === 'warning' ? 'text-dark' : '' ?>">
                                    <?= $inc['prioritat'] ?>
                                </span>
                            </td>
                            <td><?= $inc['dataInici'] ?></td>
                            <td>
                                <i class="fa-regular fa-clock me-1 text-primary"></i>
                                <?= gmdate('H:i:s', $inc['tempsTotalDedicat']) ?>
                            </td>
                            <td class="text-truncate" style="max-width: 250px; cursor: pointer;" 
                                onclick="document.getElementById('alertText').innerText=this.dataset.desc; document.getElementById('alertDescripcio').classList.remove('d-none')" 
                                data-desc="<?= htmlspecialchars($inc['descripcioIncidencia']) ?>">
                                <?= htmlspecialchars($inc['descripcioIncidencia']) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include_once "fotter.php"; ?>