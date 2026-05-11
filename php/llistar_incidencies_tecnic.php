<?php
include_once "connexio.php";

$columnesPermeses = [
    'idIncidencia' => 'i.idIncidencia',
    'prioritat'    => 'i.prioritat',
    'tipo'         => 'tp.nombre',
    'departamento' => 'd.nombre',
    'tecnico'      => 't.nombre',
    'fechaInicio'  => 'i.fechaInicio',
    'descripcion'  => 'i.descripcion',
];

$tecnic = $_GET['tecnic'] ?? null;
if (!$tecnic) {
    header("Location: tecnic.php");
    exit;
}

$orderBy  = $_GET['order'] ?? 'idIncidencia';
$orderDir = $_GET['dir']   ?? 'ASC';

if (!array_key_exists($orderBy, $columnesPermeses)) $orderBy = 'idIncidencia';
if (!in_array($orderDir, ['ASC', 'DESC']))           $orderDir = 'ASC';

$orderCol = $columnesPermeses[$orderBy];
$filtreWhere = "WHERE t.nombre = '" . $conn->real_escape_string($tecnic) . "'";
if (!empty($_GET['prioritat'])) {
    $prioritat = $conn->real_escape_string($_GET['prioritat']);
    $filtreWhere .= " AND i.prioritat = '$prioritat'";
}
if(!empty($_GET['estado'])){
    if ($_GET['estado'] === 'Oberta'){
        $filtreWhere .= " AND i.fechaFin IS NULL";
    } else {
        $filtreWhere .= " AND i.fechaFin IS NOT NULL";
    }
}else {
    $filtreWhere .= " AND i.fechaFin IS NULL";
}
$nextDir  = $orderDir === 'ASC' ? 'DESC' : 'ASC';

$sql = "
    SELECT 
        i.idIncidencia,
        i.descripcion,
        i.prioritat,
        DATE_FORMAT(i.fechaInicio, '%d/%m/%Y') AS fechaInicio,
        t.nombre AS tecnico,
        d.nombre AS departamento,
        tp.nombre AS tipo
    FROM INCIDENCIA i
    LEFT JOIN TECNICO t ON i.idTecnico = t.idTecnico
    LEFT JOIN DEPARTAMENTO d ON i.idDepartamento = d.idDepartamento
    LEFT JOIN TIPO tp ON i.idTipo = tp.idTipo
    $filtreWhere
    ORDER BY $orderCol $orderDir
";

$result = $conn->query($sql);

$capçaleres = [
    ['ID',          'idIncidencia', ''],
    ['Prioritat',   'prioritat',    ''],
    ['Tipus',       'tipo',         ''],
    ['Departament', 'departamento', ''],
    ['Data Inici',  'fechaInicio',  ''],
    ['Descripció',  null,           'd-none d-md-table-cell'],
    ['',             null,        ''],
];
?>

<?php include_once "header.php"; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Incidències de <?= htmlspecialchars($tecnic) ?></h2>
        <a href="tecnic.php" class="btn btn-outline-primary btn-sm">← Tornar</a>
    </div>
        <form method="GET" class="d-flex gap-2 mb-3">
            <input type="hidden" name="tecnic" value="<?= htmlspecialchars($tecnic) ?>">
        <select name="prioritat" class="form-select form-select-sm" style="width:auto;">
            <option value="">Totes les Prioritats</option>
            <option value="Baixa" <?= isset($_GET['prioritat']) && $_GET['prioritat'] === 'Baixa' ? 'selected' : '' ?>>Baixa</option>
            <option value="Mitja" <?= isset($_GET['prioritat']) && $_GET['prioritat'] === 'Mitja' ? 'selected' : '' ?>>Mitja</option>
            <option value="Alta"  <?= isset($_GET['prioritat']) && $_GET['prioritat'] === 'Alta'  ? 'selected' : '' ?>>Alta</option>
        </select>
        <select name="estado" class="form-select form-select-sm" style="width:auto;">
            <option value="">Tots els Estats</option>
            <option value="Oberta" <?= isset($_GET['estado']) && $_GET['estado'] === 'Oberta' ? 'selected' : '' ?>>Oberta</option>
            <option value="Tancada" <?= isset($_GET['estado']) && $_GET['estado'] === 'Tancada' ? 'selected' : '' ?>>Tancada</option>
        </select>
        <button type="submit" class="btn btn-sm btn-outline-secondary">Filtrar</button>
    </form> 
    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-success">No hi ha incidències pendents.</div>
    <?php else: ?>
        <div class="table-responsive">
            <small>
                <table class="table table-striped table-hover table-sm">
                    <thead class="table-primary">
                        <tr>
                            <?php  foreach ($capçaleres as [$label, $col, $classes]): ?>
                                <th class="<?= $classes ?>">
                                    <?php if ($col):
                                        $dir  = ($orderBy === $col) ? $nextDir : 'ASC';
                                        $icon = ($orderBy === $col)
                                            ? ($orderDir === 'ASC' ? 'fa-chevron-up' : 'fa-chevron-down')
                                            : 'fa-chevron-up text-muted';
                                    ?>
                                        <a href="?order=<?= $col ?>&dir=<?= $dir ?>&tecnic=<?= urlencode($tecnic) ?>" class="text-decoration-none text-dark">
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
                            <tr onclick="window.location='detall_incidencia_tecnic.php?idBusca=<?= $inc['idIncidencia'] ?>&tecnic=<?= urlencode($_GET['tecnic']) ?>'" style="cursor:pointer;">
                                <td><?= $inc['idIncidencia'] ?></td>
                                <td>
                                    <?php
                                    $badge = match ($inc['prioritat']) {
                                        'Alta'  => 'danger',
                                        'Mitja' => 'warning',
                                        'Baixa' => 'success',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badge ?>"><?= $inc['prioritat'] ?></span>
                                </td>
                                <td><?= $inc['tipo'] ?? '-' ?></td>
                                <td><?= $inc['departamento'] ?? '-' ?></td>
                                <td><?= $inc['fechaInicio'] ?></td>
                                <td class="d-none d-md-table-cell" title="<?= htmlspecialchars($inc['descripcion']) ?>">
                                    <?= htmlspecialchars($inc['descripcion']) ?>
                                </td>
                                <td>
                                    <form action="tancar_incidencia.php" method="post">
                                        <input type="hidden" name="idIncidencia" value="<?= $inc['idIncidencia'] ?>">
                                        <button type="submit" class="btn btn-outline-success btn-sm">Tancar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </small>
        </div>
    <?php endif; ?>
</div>

<?php include_once "fotter.php"; ?>