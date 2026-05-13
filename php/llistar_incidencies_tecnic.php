<?php
include_once "connexio.php";

$tecnic = $_GET['tecnic'] ?? null;
if (!$tecnic) {
    header("Location: tecnic.php");
    exit;
}

$filtreWhere = "WHERE t.nombre = '" . $conn->real_escape_string($tecnic) . "'";
if (!empty($_GET['prioritat'])) {
    $prioritat = $conn->real_escape_string($_GET['prioritat']);
    $filtreWhere .= " AND i.prioritat = '$prioritat'";
}
if (!empty($_GET['estado'])) {
    if ($_GET['estado'] === 'Oberta') {
        $filtreWhere .= " AND i.fechaFin IS NULL";
    } else {
        $filtreWhere .= " AND i.fechaFin IS NOT NULL";
    }
} else {
    $filtreWhere .= " AND i.fechaFin IS NULL";
}

$sql = "
    SELECT 
        i.idIncidencia,
        i.descripcion,
        i.prioritat,
        i.fechaFin,
        DATE_FORMAT(i.fechaInicio, '%d/%m/%Y') AS fechaInicio,
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
    ['Prioritat',   ''],
    ['Tipus',       ''],
    ['Departament', ''],
    ['Data Inici',  ''],
    ['Descripció',  'd-none d-md-table-cell'],
    ['',            ''],
];
?>

<?php include_once "header.php"; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Incidències de <?= htmlspecialchars($tecnic) ?></h2>
        <a href="tecnic.php" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-arrow-left"></i> Tornar</a>
    </div>
    <form method="GET" class="d-flex gap-2 mb-3">
        <input type="hidden" name="tecnic" value="<?= htmlspecialchars($tecnic) ?>">
        <select name="prioritat" class="form-select form-select-sm" style="width:auto;">
            <option value="">Totes les Prioritats</option>
            <option value="Baixa" <?= ($_GET['prioritat'] ?? '') === 'Baixa' ? 'selected' : '' ?>>Baixa</option>
            <option value="Mitja" <?= ($_GET['prioritat'] ?? '') === 'Mitja' ? 'selected' : '' ?>>Mitja</option>
            <option value="Alta" <?= ($_GET['prioritat'] ?? '') === 'Alta'  ? 'selected' : '' ?>>Alta</option>
        </select>
        <select name="estado" class="form-select form-select-sm" style="width:auto;">
            <option value="">Tots els Estats</option>
            <option value="Oberta" <?= ($_GET['estado'] ?? '') === 'Oberta'  ? 'selected' : '' ?>>Oberta</option>
            <option value="Tancada" <?= ($_GET['estado'] ?? '') === 'Tancada' ? 'selected' : '' ?>>Tancada</option>
        </select>
        <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
    </form>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-success">No hi ha incidències pendents.</div>
    <?php else: ?>
        <div class="table-responsive">
            <small>
                <table class="table table-striped table-hover table-sm">
                    <thead class="table-primary">
                        <tr>
                            <?php foreach ($capçaleres as [$label, $classes]): ?>
                                <th class="<?= $classes ?> bg-primary text-white p-2 border-primary"><?= $label ?></th>
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
                                <td onclick="event.stopPropagation()">
                                    <form action="tancar_incidencia.php" method="post">
                                        <input type="hidden" name="idIncidencia" value="<?= $inc['idIncidencia'] ?>">
                                        <input type="hidden" name="tecnic" value="<?= htmlspecialchars($tecnic) ?>">
                                        <?php if (!$inc['fechaFin']): ?>
                                            <input type="hidden" name="accio" value="tancar">
                                            <button type="submit" class="btn btn-outline-success btn-sm" title="Tancar">
                                                <i class="fa-solid fa-lock-open"></i>
                                            </button>
                                        <?php endif; ?>
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