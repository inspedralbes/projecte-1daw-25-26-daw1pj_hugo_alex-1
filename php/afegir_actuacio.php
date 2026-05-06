<?php
include_once "header.php";
?>

<div class="container">
    <h2 class="mb-4">Nova Actuació</h2>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="crear_actuacio.php">
                <div class="mb-3">
                    <label for="idIncidencia" class="form-label">Tipus:</label>
                    <select name="idIncidencia" id="idIncidencia" class="form-select">
                        <?php while($fila = $idIncidencia->fetch_assoc()): ?>
                            <option value="<?= $fila['idIncidencia'] ?>"></option>
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
                    <label for="temps" class="form-label">Temps:</label>
                    <input type="text" name="temps" id="temps" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="visible" class="form-label">Visible:</label>
                    <input type="text" name="visible" id="visible" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="comentari" class="form-label">Comentari:</label>
                    <textarea name="comentari" id="comentari" class="form-control" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Crear Incidència</button>
            </form>
        </div>
    </div>
</div>







<?php
include_once "fotter.php";
?>