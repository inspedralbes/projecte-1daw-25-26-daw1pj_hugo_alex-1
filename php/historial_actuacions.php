<?php
include_once "connexio.php";

$columnesPermeses = [
    'idAccion'     => 'a.idAccion',
    'idIncidencia' => 'a.idIncidencia',
    'tiempo'       => 'a.tiempo',
    'fechaAccion'  => 'a.fechaAccion',
    'comentario'   => 'a.comentario',
];
$orderBy  = $_GET['order'] ?? 'idIncidencia';
$orderDir = $_GET['dir']   ?? 'ASC';
if (!array_key_exists($orderBy, $columnesPermeses)) $orderBy = 'idIncidencia';
if (!in_array($orderDir, ['ASC', 'DESC']))           $orderDir = 'ASC';
$orderCol = $columnesPermeses[$orderBy];
$nextDir  = $orderDir === 'ASC' ? 'DESC' : 'ASC';
$sql = "
    SELECT 
        a.idAccion,
        a.idIncidencia,
        a.comentario,
        a.tiempo,
        DATE_FORMAT(a.fechaAccion, '%d/%m/%Y %H:%i') AS fechaAccion,
        a.visible
    FROM ACCION a
    ORDER BY $orderCol $orderDir
";
$result = $conn->query($sql);
$capçaleres = [
    ['IdActuacio',  'idAccion',     ''],
    ['IdIncidencia','idIncidencia', ''],
    ['Temps',       'tiempo',       ''],
    ['Data Accio',  'fechaAccion',  ''],
    ['Comentari',   'comentario',   'd-none d-md-table-cell'],
];
?>
<?php include_once "header.php"; ?>
<div class="container px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Historial d'Actuacions</h2>
        <a href="tecnic.php" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-arrow-left"></i>Tornar</a>
    </div>
    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">No hi ha actuacions registrades.</div>
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
                        <td><?= $inc['idAccion'] ?></td>
                        <td><?= $inc['idIncidencia'] ?></td>
                        <td><?= $inc['tiempo'] ?></td>
                        <td><?= $inc['fechaAccion'] ?></td>
                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($inc['comentario']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php include_once "fotter.php"; ?>