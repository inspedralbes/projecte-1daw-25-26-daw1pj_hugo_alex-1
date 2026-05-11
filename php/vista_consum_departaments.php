<?php
include_once "connexio.php";

// SQL simplificado (se mantiene la lógica de cálculo de segundos)
$sql = "
    SELECT
        d.idDepartamento,
        d.nombre AS nomDepartament,
        COUNT(i.idIncidencia) AS nombreIncidencies,
        IFNULL(SUM(temps_per_incidencia.tempsTotal), 0) AS tempsTotalDedicat
    FROM DEPARTAMENTO d
    LEFT JOIN INCIDENCIA i ON d.idDepartamento = i.idDepartamento
    LEFT JOIN (
        SELECT
            idIncidencia,
            SUM(TIME_TO_SEC(tiempo)) AS tempsTotal
        FROM ACCION
        GROUP BY idIncidencia
    ) AS temps_per_incidencia ON i.idIncidencia = temps_per_incidencia.idIncidencia
    GROUP BY
        d.idDepartamento,
        d.nombre
    ORDER BY d.nombre DESC
";

$result = $conn->query($sql);

$capçaleres = [
    ['Id Departament',      ''],
    ['Nom Departament',     ''],
    ['Nº Incidències',      ''],
    ['Temps Total Dedicat', ''],
];
?>

<?php include_once "header.php"; ?>

<div class="container px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Consum per Departaments</h2>
        <a href="admin.php" class="btn btn-outline-primary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Tornar
        </a>
    </div>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">No hi ha dades disponibles.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle" style="font-size: 0.75em;">
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
                            <td style="width: 1%; white-space: nowrap;" class="px-3">
                                <span class="badge rounded-pill border border-primary text-primary fw-bold" style="border-width: 2px !important;">
                                    <?= $inc['idDepartamento'] ?>
                                </span>
                            </td>

                            <td class="fw-bold"><?= htmlspecialchars($inc['nomDepartament']) ?></td>

                            <td>
                                <span class="fw-bold">
                                    #<?= $inc['nombreIncidencies'] ?>
                                </span>
                            </td>

                            <td>
                                <i class="fa-regular fa-clock me-1 text-primary"></i>
                                <?= gmdate('H:i:s', $inc['tempsTotalDedicat']) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include_once "fotter.php"; ?>