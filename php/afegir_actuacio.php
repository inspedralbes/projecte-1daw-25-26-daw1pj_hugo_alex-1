<?php
include_once "header.php";
?>

<?php
include_once "connexio.php";

$idIncidencia = $_GET['idIncidencia'] ?? null;
?>

<div class="container">
    <h2 class="mb-4">Nova Actuació</h2>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="crear_actuacio.php">
                <div class="mb-3">
                    <input type="hidden" name="idIncidencia" value="<?= $idIncidencia ?>">
                    <input type="hidden" name="tecnic" value="<?= htmlspecialchars($_GET['tecnic'] ?? '') ?>">
                    <input type="hidden" name="origen" value="<?= htmlspecialchars($_GET['origen'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="temps" class="form-label">Temps(hh:mm):</label>
                    <input type="time" name="temps" id="temps" class="form-control" min="0">
                </div>
                <div class="mb-3">
                    <label for="comentario" class="form-label">Comentari:</label>
                    <textarea name="comentario" id="comentario" class="form-control" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="visible" id="visible" class="form-check-input" value="1" checked>
                        <label for="visible" class="form-check-label">Visible per l'usuari</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Registrar Actuació</button>
                <a href="detall_incidencia_tecnic.php?idBusca=<?= $idIncidencia ?>&tecnic=<?= urlencode($_GET['tecnic'] ?? '') ?>&origen=<?= htmlspecialchars($_GET['origen'] ?? '') ?>" class="btn btn-secondary ms-2">Tornar</a>
                
            </form>
        </div>
    </div>
</div>
<?php
include_once "fotter.php";
?>