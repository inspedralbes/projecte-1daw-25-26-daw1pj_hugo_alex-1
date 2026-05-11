<?php
include_once "connexio.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int) $_POST['id'];
    $campo = $_POST['campo'] ?? '';
    $valor = $_POST['valor'] ?? '';

    if (isset($_POST['eliminar'])) {
        $conn->query("DELETE FROM INCIDENCIA WHERE idIncidencia = $id");
        header('Location: admin.php');
        exit;
    }

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

$result       = $conn->query($sql);
$tecnics      = $conn->query("SELECT * FROM TECNICO")->fetch_all(MYSQLI_ASSOC);
$tipus        = $conn->query("SELECT * FROM TIPO")->fetch_all(MYSQLI_ASSOC);
$departaments = $conn->query("SELECT * FROM DEPARTAMENTO")->fetch_all(MYSQLI_ASSOC);
?>

<?php include_once "header.php"; ?>

<div class="container-fluid px-3">
    <h2 class="mb-3">Panell d'Administració</h2>
    <p class="text-muted small mb-3">
        <i class="fa-solid fa-hand-pointer"></i> Fes clic a la descripció per veure-la completa
    </p>

    <!-- Alert guardat -->
    <div id="alertGuardat" class="alert alert-success d-none" role="alert"
        style="position:sticky; top:70px; z-index:999;">
        Canvi guardat correctament!
    </div>

    <!-- Alert descripció -->
    <div id="alertDescripcio" class="alert alert-secondary alert-dismissible d-none" role="alert"
        style="position:sticky; top:70px; z-index:999; overflow-wrap: break-word;">
        <span id="alertText"></span>
        <button type="button" class="btn-close" onclick="document.getElementById('alertDescripcio').classList.add('d-none')"></button>
    </div>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">No hi ha incidències registrades.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle" style="font-size: 0.7em;">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Prioritat</th>
                        <th>Tipus</th>
                        <th>Tècnic</th>
                        <th>Departament</th>
                        <th>Inici</th>
                        <th>Fi</th>
                        <th>Descripció</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($inc = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $inc['idIncidencia'] ?></td>

                            <!-- Prioritat -->
                            <td style="min-width: 90px;">
                                <select class="form-select form-select-sm" onchange="guardarIRecarregar(<?= $inc['idIncidencia'] ?>, 'prioritat', this.value)">
                                    <option value="Alta" <?= $inc['prioritat'] === 'Alta'  ? 'selected' : '' ?>>Alta</option>
                                    <option value="Mitja" <?= $inc['prioritat'] === 'Mitja' ? 'selected' : '' ?>>Mitja</option>
                                    <option value="Baixa" <?= $inc['prioritat'] === 'Baixa' ? 'selected' : '' ?>>Baixa</option>
                                </select>
                            </td>

                            <!-- Tipus -->
                            <td style="min-width: 110px;">
                                <select class="form-select form-select-sm" onchange="guardarIRecarregar(<?= $inc['idIncidencia'] ?>, 'idTipo', this.value)">
                                    <?php foreach ($tipus as $t): ?>
                                        <option value="<?= $t['idTipo'] ?>" <?= $inc['idTipo'] == $t['idTipo'] ? 'selected' : '' ?>>
                                            <?= $t['nombre'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <!-- Tècnic -->
                            <td style="min-width: 120px;">
                                <select class="form-select form-select-sm" onchange="guardarIRecarregar(<?= $inc['idIncidencia'] ?>, 'idTecnico', this.value)">
                                    <option value="" <?= $inc['idTecnico'] === null ? 'selected' : '' ?>> --- </option>
                                    <?php foreach ($tecnics as $t): ?>
                                        <option value="<?= $t['idTecnico'] ?>" <?= $inc['idTecnico'] == $t['idTecnico'] ? 'selected' : '' ?>>
                                            <?= $t['nombre'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <!-- Departament -->
                            <td style="min-width: 120px;">
                                <select class="form-select form-select-sm" onchange="guardarIRecarregar(<?= $inc['idIncidencia'] ?>, 'idDepartamento', this.value)">
                                    <option value="" <?= ($inc['idDepartamento'] ?? null) === null ? 'selected' : '' ?>> --- </option>
                                    <?php foreach ($departaments as $d): ?>
                                        <option value="<?= $d['idDepartamento'] ?>" <?= $inc['idDepartamento'] == $d['idDepartamento'] ? 'selected' : '' ?>>
                                            <?= $d['nombre'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <td><?= $inc['fechaInicio'] ?></td>
                            <td><?= $inc['fechaFin'] ?? 'Oberta' ?></td>

                            <!-- Descripció -->
                            <td class="descripcio-cell"
                                onclick="document.getElementById('alertText').innerText=this.dataset.desc; document.getElementById('alertDescripcio').classList.remove('d-none')"
                                data-desc="<?= htmlspecialchars($inc['descripcion']) ?>">
                                <?= htmlspecialchars($inc['descripcion']) ?>
                            </td>

                            <!-- Eliminar -->
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
        </div>
    <?php endif; ?>
</div>

<script>
    function guardarIRecarregar(id, campo, valor) {
        fetch('admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${id}&campo=${campo}&valor=${encodeURIComponent(valor)}`
        }).then(() => {
            document.getElementById('alertGuardat').classList.remove('d-none');
            setTimeout(() => location.reload(), 1000);
        });
    }
</script>

<?php include_once "fotter.php"; ?>