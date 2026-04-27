<?php
    include_once "header.php";
?>
<div class="row justify-content-center mt-5">
    <div class="col-md-8 text-center">
        <div class="p-5 mb-4 bg-light rounded-3 shadow-lg border-primary" style="border-top: 5px solid #0d6efd;">
            <div class="container-fluid py-5">
                <i class="bi bi-controller display-1 text-primary mb-4"></i>
                <h1 class="display-5 fw-bold text-dark">¡Benvingut al gestor 
                    d'incidencies!</h1>
                <p class="col-md-12 fs-4 text-muted">
                    Selecciona quin típus d' usuari ets
                </p>
                <hr class="my-4">
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="user.php" class="btn btn-primary btn-lg px-5 shadow">
                        <i class="bi bi-collection-play"></i> Usuari
                    </a>
                    <a href="tecnic.php" class="btn btn-primary btn-lg px-5 shadow">
                        <i class="bi bi-plus-circle"></i> Técnic
                    </a>
                    <a href="admin.php" class="btn btn-primary btn-lg px-5 shadow">
                        <i class="bi bi-collection-play"></i> Admin
                    </a>
                </div>
            </div>
        </div>
</body>
</html>