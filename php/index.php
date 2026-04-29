<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <style>html {font-size: clamp(12px, 2vw, 16px);}</style>
</head>
<body class="d-flex flex-column min-vh-100">
<main class="flex-grow-1 d-flex align-items-center justify-content-center">
    <div class="row justify-content-center mt-3 mt-md-5">
        <div class="col-11 col-md-8 col-lg-6 col-xl-5 text-center">
            <div class="p-3 p-md-5 mb-4 bg-light rounded-3 shadow-lg" style="border-top: 5px solid #0d6efd;">
                <div class="container-fluid py-3 py-md-5">
                    <img class="img-fluid mb-3" style="max-height: 100px;" src="resources/Logoinsti_simple.png" alt="Logo Institut Pedralbes">
                    <h1 class="h2 fw-bold text-dark">¡Benvingut al gestor d'incidencies!</h1>
                    <p class="fs-5 text-muted">Selecciona quin típus d'usuari ets</p>
                    <hr class="my-3">
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        <a href="crear_incidencia.php" class="btn btn-primary btn-lg px-5 shadow">Usuari</a>
                        <a href="tecnic.php" class="btn btn-primary btn-lg px-5 shadow">Tècnic</a>
                        <a href="admin.php" class="btn btn-primary btn-lg px-5 shadow">Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include_once "fotter.php"; ?>
</body>
</html>