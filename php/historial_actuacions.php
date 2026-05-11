<?php
include_once "connexio.php";

// 1. Intentamos capturar el ID de la incidencia de la URL para el botón "Tornar"
// Si no viene por URL, usaremos el que salga en los resultados de la tabla
$idRetorn = $_GET['idIncidencia'] ?? null;

$sql = "
    SELECT 
        a.idAccion,
        a.idIncidencia,
        a.comentario,
        a.tiempo,
        DATE_FORMAT(a.fechaAccion, '%d/%m/%Y %H:%i') AS fechaAccion,
        a.visible
    FROM ACCION a
    ORDER BY a.fechaAccion DESC
";
$result = $conn->query($sql);

$capçaleres = [
    ['Id Actuació',   ''],
    ['Id Incidència', ''],
    ['Temps',         ''],
    ['Data Acció',    ''],
    ['Comentari',     'd-none d-md-table-cell'],
];
?>
<?php include_once "header.php"; ?>

<div class="container px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Historial d'Actuacions</h2>
        
        <?php 
            // Si sabemos de qué incidencia venimos, volvemos al detalle de esa. 
            // Si no, volvemos al panel general del técnico.
            $urlRetorn = $idRetorn ? "detall_incidencia_tecnic.php?idBusca=$idRetorn" : "tecnic.php";
        ?>
        <a href="<?= $urlRetorn ?>" class="btn btn-outline-primary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Tornar
        </a>
    </div>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">No hi ha actuacions registrades.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-bottom" style="font-size: 0.75em;">
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
                    <tr onclick="window.location='detall_incidencia_tecnic.php?idBusca=<?= $inc['idIncidencia'] ?>'" style="cursor:pointer;">
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