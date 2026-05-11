<?php
include_once "header.php";
include_once "connexio.php";

$tecnics = $conn->query("SELECT * FROM TECNICO")->fetch_all(MYSQLI_ASSOC);
?>

<div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 60vh;">

    <i class="fas fa-regular fa-user fa-5x mb-4"></i>
    <h2 class="mb-4">Selecciona el teu usuari</h2>

    <form action="llistar_incidencies_tecnic.php" method="GET" class="w-100" style="max-width: 300px;">
        <select name="tecnic" class="form-select form-select-lg mb-3">
            <option value="" disabled selected>--Selecciona tècnic--</option>
            <?php foreach ($tecnics as $t): ?>
                <option value="<?= htmlspecialchars($t['nombre']) ?>">
                    <?= htmlspecialchars($t['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary btn-lg w-100 shadow">
            <i class="fa-solid fa-door-closed"></i> Accedir
        </button>
        <br>
        <br>
        
        <a href="historial_actuacions.php" class="btn btn-primary btn-lg w-100 shadow"><i class="fa-solid fa-clock"></i> Historial d'actuacions</a>
        
        <br>
        <br>
        <a href="index.php" class="btn btn-primary btn-lg w-100 shadow"> <i class="fa-solid fa-arrow-left"></i> Tornar a Portada</a>
    </form>
</div>

<?php include_once "fotter.php"; ?>