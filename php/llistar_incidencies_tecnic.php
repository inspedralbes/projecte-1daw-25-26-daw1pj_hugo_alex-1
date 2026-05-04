<?php
include_once "connexio.php";

// Recogemos el técnico de la URL
$tecnic = $_GET['tecnic'] ?? null;

// Si no hay técnico, volvemos a la página de selección
if (!$tecnic) {
    header("Location: tecnic.php");
    exit;
}

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
    WHERE t.nombre = '" . $conn->real_escape_string($tecnic) . "'
    AND i.fechaFin IS NULL
    ORDER BY 
        CASE i.prioritat
            WHEN 'Alta' THEN 1
            WHEN 'Mitja' THEN 2
            WHEN 'Baixa' THEN 3
        END
";

$result = $conn->query($sql);
?>

<?php include_once "header.php"; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Incidències de <?= htmlspecialchars($tecnic) ?></h2>
        <a href="tecnic.php" class="btn btn-outline-primary btn-sm">← Tornar</a>
    </div>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-success">No hi ha incidències pendents.</div>
    <?php else: ?>
        <div class="table-responsive">
            <small>
                <table class="table table-striped table-hover table-sm">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Descripció</th>
                            <th>Prioritat</th>
                            <th class="d-none d-md-table-cell">Tipus</th>
                            <th class="d-none d-md-table-cell">Departament</th>
                            <th>Data Inici</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($inc = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $inc['idIncidencia'] ?></td>
                            <td><?= $inc['descripcion'] ?></td>
                            <td>
                                <?php
                                $badge = match($inc['prioritat']) {
                                    'Alta'  => 'danger',
                                    'Mitja' => 'warning',
                                    'Baixa' => 'success',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $badge ?>">
                                    <?= $inc['prioritat'] ?>
                                </span>
                            </td>
                            <td class="d-none d-md-table-cell"><?= $inc['tipo'] ?? '-' ?></td>
                            <td class="d-none d-md-table-cell"><?= $inc['departamento'] ?? '-' ?></td>
                            <td><?= $inc['fechaInicio'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </small>
        </div>
    <?php endif; ?>
</div>

<?php include_once "fotter.php"; ?>