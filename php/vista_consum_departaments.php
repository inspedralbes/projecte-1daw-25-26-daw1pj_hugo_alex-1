<?php
include_once "connexio.php";

$columnesPermeses = [
    'idDepartamento'    => 'idDepartamento',
    'nomDepartament'    => 'nomDepartament',
    'nombreIncidencies' => 'nombreIncidencies',
    'tempsTotalDedicat' => 'tempsTotalDedicat',
];

$orderBy  = $_GET['order'] ?? 'idDepartamento';
$orderDir = $_GET['dir']   ?? 'ASC';

if (!array_key_exists($orderBy, $columnesPermeses)) $orderBy = 'idDepartamento';
if (!in_array($orderDir, ['ASC', 'DESC']))           $orderDir = 'ASC';

$orderCol = $columnesPermeses[$orderBy];
$nextDir  = $orderDir === 'ASC' ? 'DESC' : 'ASC';

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
    ORDER BY $orderCol $orderDir

";

$result = $conn->query($sql);

$capçaleres = [
    ['Id Departament',          'idDepartamento',             ''],
    ['Nom Departament',       'nomDepartament',         ''],
    ['Nº Incidencies',               'nombreIncidencies',               ''],
    ['Temps Total Dedicat',  'tempsTotalDedicat',  'd-none d-md-table-cell'],
];
?>

<?php include_once "header.php"; ?>

<div class="container-fluid px-3">
    <h2 class="mb-4">Informe de Tècnics</h2>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">No hi ha incidències registrades.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-bottom" style="font-size: 0.75em;">
                <thead class="table-primary">
                    <tr>
                        <?php foreach ($capçaleres as [$label, $col, $classes]): ?>
                            <th class="<?= $classes ?>">
                                <?php if ($col):
                                    $dir  = ($orderBy === $col) ? $nextDir : 'ASC';
                                    $icon = ($orderBy === $col)
                                        ? ($orderDir === 'ASC' ? 'fa-chevron-up' : 'fa-chevron-down')
                                        : 'fa-chevron-up text-muted';
                                ?>
                                    <a href="?order=<?= $col ?>&dir=<?= $dir ?>" class="text-decoration-none text-dark">
                                        <?= $label ?> <i class="fa-solid <?= $icon ?>" style="font-size:0.75em;"></i>
                                    </a>
                                <?php else: ?>
                                    <?= $label ?>
                                <?php endif; ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($inc = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $inc['idDepartamento'] ?></td>
<td><?= $inc['nomDepartament'] ?></td>
<td><?= $inc['nombreIncidencies'] ?></td>
<td><?= gmdate('H:i:s', $inc['tempsTotalDedicat']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include_once "fotter.php"; ?>