<?php include_once "header.php"; ?>

<div class="d-flex align-items-center justify-content-center flex-grow-1">
    <div class="col-11 col-md-8 col-lg-6 col-xl-5 text-center">
        <div class="p-3 p-md-5 mb-4 bg-light rounded-3 shadow-lg" style="border-top: 5px solid #0d6efd;">
            <div class="container-fluid py-3 py-md-5">
                <img class="img-fluid mb-3" style="max-height: 100px;" src="resources/Logoinsti_simple.png" alt="Logo">
                <h1 class="h2 fw-bold text-dark">¡Benvingut al gestor d'incidencies!</h1>
                <p class="fs-5 text-muted">Selecciona quin típus d'usuari ets</p>
                <hr class="my-3">
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="formulari_incidencia.php" class="btn btn-primary btn-lg px-5 shadow">Usuari</a>
                    <a href="tecnic.php" class="btn btn-primary btn-lg px-5 shadow">Tècnic</a>
                    <a href="admin.php" class="btn btn-primary btn-lg px-5 shadow">Admin</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "fotter.php"; ?>