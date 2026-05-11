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
$filtreWhere = "WHERE 1=1";
if (!empty($_GET['tipus'])) {
    $tipus = $conn->real_escape_string($_GET['tipus']);
    $filtreWhere .= " AND i.idTipo = '$tipus'";
}
if (!empty($_GET['estat'])) {
    if ($_GET['estat'] === 'oberta'){
        $filtreWhere .= " AND i.fechaFin IS NULL";
    } else {
        $filtreWhere .= " AND i.fechaFin IS NOT NULL";
    }
}
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
    $filtreWhere
    ORDER BY $orderCol $orderDir
";

$result = $conn->query($sql);

$capçaleres = [
    ['ID',          'idIncidencia', ''],
    ['Tipus',       'tipo',         ''],
    ['Departament', 'departamento', ''],
    ['Tècnic',      'tecnico',      ''],
    ['Data Inici',  'fechaInicio',  ''],
    ['Data Fi',     'fechaFin',     ''],
    ['Descripció',  null,           'd-none d-md-table-cell'],
];
?>

<?php include_once "header.php"; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Llistat d'Incidències</h2>
        <a href="formulari_incidencia.php" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-arrow-left"></i> Tornar</a>
    </div>

<div class="container-fluid px-3">
    <h2 class="mb-4">Llistat d'Incidències</h2>

    <a href="formulari_incidencia.php" class="btn btn-outline-primary btn-sm">← Tornar</a>
    <br><br>
    <form method="GET" class="d-flex gap-2 mb-3">
    <select name="tipus" class="form-select form-select-sm" style="width:auto;">
        <option value="">Tots els Tipus</option>
        <?php
        $tiposFilter = $conn->query("SELECT idTipo, nombre FROM TIPO");
        while($t = $tiposFilter->fetch_assoc()):
        ?>
            <option value="<?= $t['idTipo'] ?>" <?= ($_GET['tipus'] ?? '') == $t['idTipo'] ? 'selected' : '' ?>>
                <?= $t['nombre'] ?>
            </option>
        <?php endwhile; ?>
    </select>
    <select name="estat" class="form-select form-select-sm" style="width:auto;">
        <option value="">Tots els Estats</option>
        <option value="oberta" <?= ($_GET['estat'] ?? '') === 'oberta' ? 'selected' : '' ?>>Oberta</option>
        <option value="tancada" <?= ($_GET['estat'] ?? '') === 'tancada' ? 'selected' : '' ?>>Tancada</option>
    </select>
    <button type="submit" class="btn btn-sm btn-outline-secondary">Filtrar</button>
</form>
   
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
                        <tr onclick="window.location='detall_incidencia.php?idBusca=<?= $inc['idIncidencia'] ?>'" style="cursor:pointer;">
                            <td><?= $inc['idIncidencia'] ?></td>
                            <td><?= $inc['tipo'] ?? '-' ?></td>
                            <td><?= $inc['departamento'] ?? '-' ?></td>
                            <td><?= $inc['tecnico'] ?? 'Sense assignar' ?></td>
                            <td><?= $inc['fechaInicio'] ?></td>
                            <td><?= $inc['fechaFin'] ?? 'Oberta' ?></td>
                            <td class="d-none d-md-table-cell"><?= $inc['descripcion'] ?>
                                <?= htmlspecialchars($inc['descripcion']) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="formulari_incidencia.php" class="btn btn-secondary mb-3 mt-3"><i class="fa-solid fa-plus"></i> Nova incidencia</a>
</div>

<?php include_once "fotter.php"; ?>