<?php
// Conectamos a la base de datos
include_once "connexio.php";

// Pedimos todas las incidencias a la base de datos
$sql = "
    SELECT 
        i.idIncidencia,
        i.descripcion,
        i.prioritat,
        i.fechaInicio,
        i.fechaFin,
        t.nombre AS tecnico,
        d.nombre AS departamento,
        tp.nombre AS tipo
    FROM INCIDENCIA i
    LEFT JOIN TECNICO t ON i.idTecnico = t.idTecnico
    LEFT JOIN DEPARTAMENTO d ON i.idDepartamento = d.idDepartamento
    LEFT JOIN TIPO tp ON i.idTipo = tp.idTipo
    ORDER BY i.idIncidencia
";

// Ejecutamos la consulta
$result = $conn->query($sql);
?>

<?php include_once "header.php"; ?>

<div class="container">
    <h2 class="mb-4">Llistat d'Incidències</h2>

    <?php if ($result->num_rows === 0): ?>
        <!-- Si no hay incidencias mostramos un mensaje -->
        <div class="alert alert-info">No hi ha incidències registrades.</div>
    <?php else: ?>
        <!-- Si hay incidencias las mostramos en una tabla -->
        <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Descripció</th>
                    <th>Prioritat</th>
                    <th>Tipus</th>
                    <th>Departament</th>
                    <th>Tècnic</th>
                    <th>Data Inici</th>
                    <th>Data Fi</th>
                </tr>
            </thead>
            <tbody>
                <!--Guardamos los resultados de las columnas en "$inc", que es $result->fetch_assoc() (el fetch_assoc() coge una fila de la consulta cada vez que se ejecuta) -->
                <?php while ($inc = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $inc['idIncidencia'] ?></td>
                    <td><?= $inc['descripcion'] ?></td>
                    <td><?= $inc['prioritat'] ?></td>
                    <td><?= $inc['tipo'] ?? '-' ?></td>
                    <td><?= $inc['departamento'] ?? '-' ?></td>
                    <td><?= $inc['tecnico'] ?? 'Sense assignar' ?></td>
                    <td><?= $inc['fechaInicio'] ?></td>
                    <td><?= $inc['fechaFin'] ?? 'Oberta' ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include_once "fotter.php"; ?>