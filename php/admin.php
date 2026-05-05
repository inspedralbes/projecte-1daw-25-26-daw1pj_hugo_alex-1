<?php
include_once "connexio.php";


// Guardar cambio via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int) $_POST['id'];
    $campo = $_POST['campo'] ?? '';
    $valor = $_POST['valor'] ?? '';

    // Eliminar
    if (isset($_POST['eliminar'])) {
        $conn->query("DELETE FROM INCIDENCIA WHERE idIncidencia = $id");
        header('Location: admin.php');
        exit;
    }

    // Guardar camp
    $camposPermesos = ['prioritat', 'idTipo', 'idTecnico', 'idDepartamento'];
    if (in_array($campo, $camposPermesos)) {
        if ($valor === '') $valor = 'NULL';
        else $valor = is_numeric($valor) ? (int)$valor : "'" . $conn->real_escape_string($valor) . "'";
        $conn->query("UPDATE INCIDENCIA SET $campo = $valor WHERE idIncidencia = $id");
    }
    exit;
}

$sql = "
    SELECT 
        i.idIncidencia,
        i.descripcion,
        i.prioritat,
        i.idTipo,
        i.idTecnico,
        i.idDepartamento,
        DATE_FORMAT(i.fechaInicio, '%d/%m/%Y') AS fechaInicio,
        DATE_FORMAT(i.fechaFin, '%d/%m/%Y') AS fechaFin,
        t.nombre AS tecnico,
        d.nombre AS departamento,
        tp.nombre AS tipo
    FROM INCIDENCIA i
    LEFT JOIN TECNICO t ON i.idTecnico = t.idTecnico
    LEFT JOIN DEPARTAMENTO d ON i.idDepartamento = d.idDepartamento
    LEFT JOIN TIPO tp ON i.idTipo = tp.idTipo
    ORDER BY FIELD(i.prioritat, 'Alta', 'Mitja', 'Baixa')
";

$result  = $conn->query($sql);
$tecnics = $conn->query("SELECT * FROM TECNICO")->fetch_all(MYSQLI_ASSOC);
$tipus   = $conn->query("SELECT * FROM TIPO")->fetch_all(MYSQLI_ASSOC);
$departaments = $conn->query("SELECT * FROM DEPARTAMENTO")->fetch_all(MYSQLI_ASSOC);
?>

<?php include_once "header.php"; ?>

<div class="container">
    <h2 class="mb-4">Panell d'Administració</h2>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">No hi ha incidències registrades.</div>
    <?php else: ?>
        <div class="table-responsive">
            <small>
                <table class="table table-striped table-hover table-sm align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>id</th>
                            <th>Prioritat</th>
                            <th class="d-none d-md-table-cell" style="min-width: 120px;">Tipus</th>
                            <th style="min-width: 130px;">Tècnic</th>
                            <th class="d-none d-md-table-cell">Departament</th>
                            <th class="d-none d-md-table-cell">Data Inici</th>
                            <th class="d-none d-md-table-cell">Data Fi</th>
                            <th>Descripció</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($inc = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $inc['idIncidencia'] ?></td>

                                <!-- Prioritat -->
                                <td>
                                    <?php
                                    $badge = match ($inc['prioritat']) {
                                        'Alta'  => 'danger',
                                        'Mitja' => 'warning',
                                        'Baixa' => 'success',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badge ?>"
                                        style="cursor:pointer;"
                                        onclick="this.nextElementSibling.style.display='block'; this.style.display='none';">
                                        <?= $inc['prioritat'] ?> ▼
                                    </span>
                                    <select class="form-select form-select-sm"
                                        style="display:none; width:100px;"
                                        onchange="guardar(<?= $inc['idIncidencia'] ?>, 'prioritat', this.value); location.reload();"
                                        onblur="this.style.display='none'; this.previousElementSibling.style.display='inline';">
                                        <option value="Alta" <?= $inc['prioritat'] === 'Alta'  ? 'selected' : '' ?>>Alta</option>
                                        <option value="Mitja" <?= $inc['prioritat'] === 'Mitja' ? 'selected' : '' ?>>Mitja</option>
                                        <option value="Baixa" <?= $inc['prioritat'] === 'Baixa' ? 'selected' : '' ?>>Baixa</option>
                                    </select>
                                </td>

                                <!-- Tipus -->
                                <td class="d-none d-md-table-cell">
                                    <select class="form-select form-select-sm" onchange="guardar(<?= $inc['idIncidencia'] ?>, 'idTipo', this.value)">
                                        <?php foreach ($tipus as $t): ?>
                                            <option value="<?= $t['idTipo'] ?>" <?= $inc['idTipo'] == $t['idTipo'] ? 'selected' : '' ?>>
                                                <?= $t['nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <!-- Tècnic -->
                                <td style="min-width: 130px;"><!-- select tecnic -->
                                    <select class="form-select form-select-sm" onchange="guardar(<?= $inc['idIncidencia'] ?>, 'idTecnico', this.value)">
                                        <option value="" <?= $inc['idTecnico'] === null ? 'selected' : '' ?>> --- </option>
                                        <?php foreach ($tecnics as $t): ?>
                                            <option value="<?= $t['idTecnico'] ?>" <?= $inc['idTecnico'] == $t['idTecnico'] ? 'selected' : '' ?>>
                                                <?= $t['nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <!-- Departament -->
                                <td class="d-none d-md-table-cell" style="min-width: 130px;">
                                    <select class="form-select form-select-sm" onchange="guardar(<?= $inc['idIncidencia'] ?>, 'idDepartamento', this.value)">
                                        <option value="" <?= ($inc['idDepartamento'] ?? null) === null ? 'selected' : '' ?>> --- </option>
                                        <?php foreach ($departaments as $d): ?>
                                            <option value="<?= $d['idDepartamento'] ?>" <?= $inc['idDepartamento'] == $d['idDepartamento'] ? 'selected' : '' ?>>
                                                <?= $d['nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="d-none d-md-table-cell"><?= $inc['fechaInicio'] ?></td>
                                <td class="d-none d-md-table-cell"><?= $inc['fechaFin'] ?? 'Oberta' ?></td>
                                <td><?= $inc['descripcion'] ?></td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Segur que vols eliminar la incidència #<?= $inc['idIncidencia'] ?>?')">
                                        <input type="hidden" name="eliminar" value="1">
                                        <input type="hidden" name="id" value="<?= $inc['idIncidencia'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<script>
    function guardar(id, campo, valor) {
        fetch('admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${id}&campo=${campo}&valor=${encodeURIComponent(valor)}`
        });
    }
</script>

<?php include_once "fotter.php"; ?>