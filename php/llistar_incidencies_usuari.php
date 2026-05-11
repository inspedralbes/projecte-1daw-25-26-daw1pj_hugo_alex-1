<?php
include_once "connexio.php";

$filtreWhere = "WHERE 1=1";
if (!empty($_GET['tipus'])) {
    $tipus = $conn->real_escape_string($_GET['tipus']);
    $filtreWhere .= " AND i.idTipo = '$tipus'";
}
if (!empty($_GET['estat'])) {
    if ($_GET['estat'] === 'oberta') {
        $filtreWhere .= " AND i.fechaFin IS NULL";
    } else {
        $filtreWhere .= " AND i.fechaFin IS NOT NULL";
    }
}

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
    ORDER BY i.idIncidencia ASC
";

$result = $conn->query($sql);

$capçaleres = [
    ['ID',          ''],
    ['Tipus',       ''],
    ['Departament', ''],
    ['Tècnic',      ''],
    ['Data Inici',  ''],
    ['Data Fi',     ''],
    ['Descripció',  'd-none d-md-table-cell'],
];
?>

<?php include_once "header.php"; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Llistat d'Incidències</h2>
        <a href="formulari_incidencia.php" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-arrow-left"></i> Tornar</a>
    </div>
    <form method="GET" class="d-flex gap-2 mb-3">
        <select name="tipus" class="form-select form-select-sm" style="width:auto;">
            <option value="">Tots els Tipus</option>
            <?php
            $tiposFilter = $conn->query("SELECT idTipo, nombre FROM TIPO");
            while ($t = $tiposFilter->fetch_assoc()):
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
        <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
    </form>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">No hi ha incidències registrades.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-bottom" style="font-size: 0.75em;">
                <thead class="table-primary">
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
                        <tr onclick="window.location='detall_incidencia.php?idBusca=<?= $inc['idIncidencia'] ?>'" style="cursor:pointer;">
                            <td><?= $inc['idIncidencia'] ?></td>
                            <td><?= $inc['tipo'] ?? '-' ?></td>
                            <td><?= $inc['departamento'] ?? '-' ?></td>
                            <td><?= $inc['tecnico'] ?? 'Sense assignar' ?></td>
                            <td><?= $inc['fechaInicio'] ?></td>
                            <td><?= $inc['fechaFin'] ?? 'Oberta' ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars($inc['descripcion']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="formulari_incidencia.php" class="btn btn-secondary mb-3 mt-3"><i class="fa-solid fa-plus"></i> Nova incidencia</a>
</div>

<?php include_once "fotter.php"; ?>