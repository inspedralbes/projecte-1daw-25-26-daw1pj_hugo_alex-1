<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body class="d-flex flex-column min-vh-100">
    <main class="flex-grow-1">
<div class="row justify-content-center mt-5">
    <div class="col-md-8 text-center">
        <div class="p-5 mb-4 bg-light rounded-3 shadow-lg border-primary" style="border-top: 5px solid #0d6efd;">
            <div class="container-fluid py-5">
                <i class="bi bi-controller display-1 text-primary mb-4"></i>

                <img class="img-fluid" src="resources/Logoinsti_simple.png" alt="Logo Institut Pedralbes">
                
                <h1 class="display-5 fw-bold text-dark">¡Benvingut al gestor 
                    d'incidencies!</h1>
                <p class="col-md-12 fs-4 text-muted">
                    Selecciona quin típus d' usuari ets
                </p>
                <hr class="my-4">
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="crear_incidencia.php" class="btn btn-primary btn-lg px-5 shadow">
                        <div bclass="bi bi-collection-play">Usuari</div>
                    </a>
                    <a href="tecnic.php" class="btn btn-primary btn-lg px-5 shadow">
                        <div class="bi bi-plus-circle">Técnic</div>
                    </a>
                    <a href="admin.php" class="btn btn-primary btn-lg px-5 shadow">
                        <div class="bi bi-collection-play">Admin</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
    include_once "fotter.php"
?>
</body>
</html>