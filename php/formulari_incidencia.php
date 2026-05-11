<?php
require_once 'connexio.php';
$tipus = $conn->query("select idTipo, nombre from TIPO");
$departaments = $conn->query("select idDepartamento, nombre from DEPARTAMENTO");
?>
<?php include_once "header.php"; ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Nova Incidència</h2>
        <a href="index.php" class="btn btn-outline-primary btn-sm">← Tornar</a>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="crear_incidencia.php">
                <div class="mb-3">
                    <label for="idTipus" class="form-label">Tipus:</label>
                    <select name="idTipus" id="idTipus" class="form-select">
                        <?php while($fila = $tipus->fetch_assoc()): ?>
                            <option value="<?= $fila['idTipo'] ?>"><?= $fila['nombre'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="idDepartament" class="form-label">Departament:</label>
                    <select name="idDepartament" id="idDepartament" class="form-select">
                        <?php while($fila = $departaments->fetch_assoc()): ?>
                            <option value="<?= $fila['idDepartamento'] ?>"><?= $fila['nombre'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="descripcio" class="form-label">Descripció:</label>
                    <textarea name="descripcio" id="descripcio" class="form-control" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Crear Incidència</button>
            </form>
            <a href="llistar_incidencies_usuari.php" class="btn btn-secondary mt-2">Llistar Incidències</a>
        </div>
    </div>
</div>
<?php include_once "fotter.php"; ?>