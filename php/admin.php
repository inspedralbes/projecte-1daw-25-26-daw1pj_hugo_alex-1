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

    if (isset($_POST['tancar'])) {
        if ($_POST['tancar'] == '1') {
            $conn->query("UPDATE INCIDENCIA SET fechaFin = NOW() WHERE idIncidencia = $id");
        } else {
            $conn->query("UPDATE INCIDENCIA SET fechaFin = NULL WHERE idIncidencia = $id");
        }
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
    ORDER BY (i.fechaFin IS NOT NULL), FIELD(i.prioritat, 'Alta', 'Mitja', 'Baixa')
";

$result       = $conn->query($sql);
$tecnics      = $conn->query("SELECT * FROM TECNICO")->fetch_all(MYSQLI_ASSOC);
$tipus        = $conn->query("SELECT * FROM TIPO")->fetch_all(MYSQLI_ASSOC);
$departaments = $conn->query("SELECT * FROM DEPARTAMENTO")->fetch_all(MYSQLI_ASSOC);
?>

<?php include_once "header.php"; ?>

<div class="container px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Panell d'Administració</h2>
        <a href="index.php" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-arrow-left"></i> Tornar</a>
    </div>

    <div class="mb-3">
        <a href="vista_informe_tecnics.php" class="btn btn-outline-primary btn-sm me-2">Informe de Tècnics</a>
        <a href="vista_consum_departaments.php" class="btn btn-outline-primary btn-sm">Consum per Departaments</a>
    </div>

    <div id="alertGuardat" class="alert alert-success d-none shadow-sm" role="alert" style="position:fixed; top:20px; right:20px; z-index:1050;">
        <i class="fa-solid fa-check-circle me-2"></i> Operació realitzada correctament!
    </div>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info border-info">No hi ha incidències registrades.</div>
    <?php else: ?>
        <div class="table-responsive mb-5">
            <table class="table table-striped table-hover table-sm align-middle" style="font-size: 0.72em; min-width: 700px;">
                <thead>
                    <tr>
                        <th class="bg-primary text-white p-2 border-primary">ID</th>
                        <th class="bg-primary text-white p-2 border-primary">Prioritat</th>
                        <th class="bg-primary text-white p-2 border-primary">Tipus</th>
                        <th class="bg-primary text-white p-2 border-primary">Tècnic</th>
                        <th class="bg-primary text-white p-2 border-primary">Departament</th>
                        <th class="bg-primary text-white p-2 border-primary" style="width: 85px;">Inici</th>
                        <th class="bg-primary text-white p-2 border-primary" style="width: 85px;">Fi</th>
                        <th class="bg-primary text-white p-2 border-primary d-none d-md-table-cell">Descripció</th>
                        <th class="bg-primary text-white p-2 border-primary text-center">Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($inc = $result->fetch_assoc()): ?>
                        <tr style="cursor:pointer;" onclick="window.location='detall_incidencia_tecnic.php?idBusca=<?= $inc['idIncidencia'] ?>&origen=admin'">
                            <td class="text-primary fw-bold">#<?= $inc['idIncidencia'] ?></td>
                            <td style="width: 90px;" onclick="event.stopPropagation()">
                                <select class="form-select form-select-sm py-0 ps-1" style="font-size: 0.95em;" onchange="guardarCanvi(<?= $inc['idIncidencia'] ?>, 'prioritat', this.value)">
                                    <option value="Alta" <?= $inc['prioritat'] === 'Alta'  ? 'selected' : '' ?>>Alta</option>
                                    <option value="Mitja" <?= $inc['prioritat'] === 'Mitja' ? 'selected' : '' ?>>Mitja</option>
                                    <option value="Baixa" <?= $inc['prioritat'] === 'Baixa' ? 'selected' : '' ?>>Baixa</option>
                                </select>
                            </td>
                            <td style="width: 100px;" onclick="event.stopPropagation()">
                                <select class="form-select form-select-sm py-0 ps-1" style="font-size: 0.95em;" onchange="guardarCanvi(<?= $inc['idIncidencia'] ?>, 'idTipo', this.value)">
                                    <?php foreach ($tipus as $t): ?>
                                        <option value="<?= $t['idTipo'] ?>" <?= $inc['idTipo'] == $t['idTipo'] ? 'selected' : '' ?>>
                                            <?= $t['nombre'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td style="width: 110px;" onclick="event.stopPropagation()">
                                <select class="form-select form-select-sm py-0 ps-1" style="font-size: 0.95em;" onchange="guardarCanvi(<?= $inc['idIncidencia'] ?>, 'idTecnico', this.value)">
                                    <option value="" <?= $inc['idTecnico'] === null ? 'selected' : '' ?>>---</option>
                                    <?php foreach ($tecnics as $t): ?>
                                        <option value="<?= $t['idTecnico'] ?>" <?= $inc['idTecnico'] == $t['idTecnico'] ? 'selected' : '' ?>>
                                            <?= $t['nombre'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td style="width: 110px;" onclick="event.stopPropagation()">
                                <select class="form-select form-select-sm py-0 ps-1" style="font-size: 0.95em;" onchange="guardarCanvi(<?= $inc['idIncidencia'] ?>, 'idDepartamento', this.value)">
                                    <option value="" <?= ($inc['idDepartamento'] ?? null) === null ? 'selected' : '' ?>>---</option>
                                    <?php foreach ($departaments as $d): ?>
                                        <option value="<?= $d['idDepartamento'] ?>" <?= $inc['idDepartamento'] == $d['idDepartamento'] ? 'selected' : '' ?>>
                                            <?= $d['nombre'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><?= $inc['fechaInicio'] ?></td>
                            <td class="<?= $inc['fechaFin'] ? 'style="color: #555;"' : 'text-success fw-bold' ?>">
                                <?= $inc['fechaFin'] ?? 'Oberta' ?>
                            </td>
                            <td class="d-none d-md-table-cell text-truncate" style="max-width: 150px;">
                                <?= htmlspecialchars($inc['descripcion']) ?>
                            </td>
                            <td class="text-nowrap text-center" onclick="event.stopPropagation()">
                                <div class="d-flex justify-content-center align-items-center" style="gap: 4px;">
                                    <div style="width: 30px;">
                                        <?php if (!$inc['fechaFin']): ?>
                                            <button onclick="tancarIncidencia(<?= $inc['idIncidencia'] ?>)" class="btn btn-outline-success btn-sm" title="Tancar" style="width: 30px;">
                                                <i class="fa-solid fa-lock-open"></i>
                                            </button>
                                        <?php else: ?>
                                            <button onclick="obrirIncidencia(<?= $inc['idIncidencia'] ?>)" class="btn btn-secondary btn-sm" title="Obrir" style="width: 30px;">
                                                <i class="fa-solid fa-lock"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <form method="POST" class="m-0" onsubmit="return confirm('Eliminar?')">
                                        <input type="hidden" name="eliminar" value="1">
                                        <input type="hidden" name="id" value="<?= $inc['idIncidencia'] ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar" style="width: 30px;">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
    function guardarCanvi(id, campo, valor) {
        enviarPeticio(`id=${id}&campo=${campo}&valor=${encodeURIComponent(valor)}`);
    }

    function tancarIncidencia(id) {
        if (confirm(`Vols tancar la incidència #${id}?`)) {
            enviarPeticio(`id=${id}&tancar=1`);
        }
    }

    function obrirIncidencia(id) {
        if (confirm(`Vols obrir la incidència #${id}?`)) {
            enviarPeticio(`id=${id}&tancar=0`);
        }
    }

    function enviarPeticio(bodyContent) {
        const alertBox = document.getElementById('alertGuardat');
        fetch('admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: bodyContent
        }).then(() => {
            alertBox.classList.remove('d-none');
            setTimeout(() => {
                location.reload();
            }, 800);
        });
    }
</script>

<?php include_once "fotter.php"; ?>