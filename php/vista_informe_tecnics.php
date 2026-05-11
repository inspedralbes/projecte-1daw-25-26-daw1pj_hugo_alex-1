<?php
include_once "connexio.php";

$columnesPermeses = [
    'nomTecnic'     => 'nombre',
    'idIncidencia' => 'idIncidencia',
    'prioritat'       => 'prioritat',
    'dataInici'  => 'fechaAccion',
    'tempsTotalDedicat'   => 'tempsTotalDedicat',
    'descripcioIncidencia'   => 'descripcion',
];

$orderBy  = $_GET['order'] ?? 'idIncidencia';
$orderDir = $_GET['dir']   ?? 'ASC';

if (!array_key_exists($orderBy, $columnesPermeses)) $orderBy = 'idIncidencia';
if (!in_array($orderDir, ['ASC', 'DESC']))           $orderDir = 'ASC';

$orderCol = $columnesPermeses[$orderBy];
$nextDir  = $orderDir === 'ASC' ? 'DESC' : 'ASC';

$sql = "
    SELECT
    t.idTecnico,
    t.nombre AS nomTecnic,
    i.prioritat,
    i.idIncidencia,
    i.descripcion AS descripcioIncidencia,
    i.fechaInicio AS dataInici,
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
    i.fechaInicio;

";

$result = $conn->query($sql);

$capçaleres = [
    ['Nombre Técnic',          'nombre',             ''],
    ['IdIncidencia',       'idIncidencia',         ''],
    ['Prioritat',               'prioritat',               ''],
    ['Data Inici',      'fechaInicio',               ''],
    ['Temps Total Dedicat',  'tempsTotalDedicat',  'd-none d-md-table-cell'],
    ['Descripcio Incidencia',  'descripcioIncidencia',  'd-none d-md-table-cell'],
];
?>

<?php include_once "header.php"; ?>

<div class="container px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Informe de Tècnics</h2>
        <a href="admin.php" class="btn btn-outline-primary btn-sm">← Tornar</a>
    </div>
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
                            <td><?= $inc['nomTecnic'] ?></td>
                            <td><?= $inc['idIncidencia'] ?></td>
                            <td><?= $inc['prioritat'] ?></td>
                            <td><?= $inc['dataInici'] ?></td>
                            <td><?= gmdate('H:i:s', $inc['tempsTotalDedicat']) ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars($inc['descripcioIncidencia']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include_once "fotter.php"; ?>