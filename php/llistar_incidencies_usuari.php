<?php
include_once "connexio.php";

$columnesPermeses = [
    'idIncidencia' => 'i.idIncidencia',
    'tipo'         => 'tp.nombre',
    'departamento' => 'd.nombre',
    'tecnico'      => 't.nombre',
    'fechaInicio'  => 'i.fechaInicio',
    'fechaFin'     => 'i.fechaFin',
    'descripcion'  => 'i.descripcion',
];

$orderBy  = $_GET['order'] ?? 'idIncidencia';
$orderDir = $_GET['dir']   ?? 'ASC';

if (!array_key_exists($orderBy, $columnesPermeses)) $orderBy = 'idIncidencia';
if (!in_array($orderDir, ['ASC', 'DESC']))           $orderDir = 'ASC';

$orderCol = $columnesPermeses[$orderBy];
$nextDir  = $orderDir === 'ASC' ? 'DESC' : 'ASC';

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
    ORDER BY $orderCol $orderDir
";

$result = $conn->query($sql);

// Capçaleres: [label, columna, classes]
$capçaleres = [
    ['ID',          'idIncidencia', ''],
    ['Tipus',       'tipo',         'd-none d-md-table-cell'],
    ['Departament', 'departamento', 'd-none d-md-table-cell'],
    ['Tècnic',      'tecnico',      ''],
    ['Data Inici',  'fechaInicio',  ''],
    ['Data Fi',     'fechaFin',     'd-none d-md-table-cell text-nowrap'],
    ['Descripció',  null,  ''],
];
?>

<?php include_once "header.php"; ?>

<div class="container px-2">
    <h2 class="mb-4">Llistat d'Incidències</h2>
    <div class="container">
        <a href="formulari_incidencia.php" class="btn btn-secondary mb-3">Nova incidencia</a>
    </div>
    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">No hi ha incidències registrades.</div>
    <?php else: ?>
        <br>
        <div class="table-responsive">
            <small>
                <table class="table table-striped table-hover table-lg">
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
                                <td><?= $inc['idIncidencia'] ?></td>
                                <td class="d-none d-md-table-cell"><?= $inc['tipo'] ?? '-' ?></td>
                                <td class="d-none d-md-table-cell"><?= $inc['departamento'] ?? '-' ?></td>
                                <td class="text-nowrap"><?= $inc['tecnico'] ?? 'Sense assignar' ?></td>
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