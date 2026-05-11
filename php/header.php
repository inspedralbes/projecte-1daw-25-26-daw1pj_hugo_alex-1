<?php include_once "logger.php"; ?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <link rel="icon" type="image/x-icon" href="resources/Logoinsti_simple_icono.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Gestor d'incidencies</title>
    <style>
        html {
            font-size: clamp(12px, 2vw, 16px);
        }

        td.editable:hover {
            background-color: #d1d1d1;
        }

        td.editable::after {
            font-size: 0.7em;
            color: #aaa;
        }

        .descripcio-cell {
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }

        .descripcio-cell.expanded {
            white-space: normal;
            overflow: visible;
        }

        @media (max-width: 767px) {
            .navbar {
                min-height: 70px;
            }
        }

        /* Tamaño por defecto para escritorio */
        .navbar-brand img {
            height: 40px;
            transition: height 0.3s ease;
            /* Opcional: para que el cambio sea suave */
        }

        /* Ajuste para móviles */
        @media (max-width: 575px) {
            .navbar-brand img {
                height: 35px;
                /* Reducimos la altura para ganar espacio */
            }

            .navbar-brand {
                margin-right: 5px;
                /* Reducimos el margen lateral */
            }
        }
    </style>

</head>


<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-light bg-light border-bottom sticky-top mb-4">
        <div class="container-fluid gap-2">

            <!-- Logo -->
            <a class="navbar-brand flex-shrink-0" href="index.php">
                <img src="resources/Logoinsti_amb_lletres.png" alt="Logo" height="40">
            </a>

            <!-- Mòbil: barra + botons a la mateixa línia que el logo -->
            <div class="d-flex gap-2 d-md-none ms-auto align-items-center">
                <form action="detall_incidencia.php" method="GET" class="input-group" style="max-width: 160px;">
                    <span class="input-group-text">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="number" name="idBusca" class="form-control" placeholder="Id...">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
                <a href="admin_logs.php" class="btn btn-outline-primary">
                    <i class="fa-solid fa-chart-line"></i>
                </a>
                <a href="index.php" class="btn btn-outline-primary">
                    <i class="fa-solid fa-house"></i>
                </a>
            </div>

            <!-- Escriptori: barra centrada + botons a la dreta -->
            <form action="detall_incidencia.php" method="GET" class="input-group d-none d-md-flex position-absolute start-50 translate-middle-x" style="max-width: 260px;">
                <span class="input-group-text">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="number" name="idBusca" class="form-control" placeholder="Id incidencia...">
                <button class="btn btn-primary" type="submit">Cerca</button>
            </form>

            <div class="d-none d-md-flex gap-1 ms-auto">
                <a href="admin_logs.php" class="btn btn-outline-primary" title="Estadistiques de Logs">
                    <i class="fa-solid fa-chart-line"></i>
                </a>
                <a href="index.php" class="btn btn-outline-primary">
                    <i class="fa-solid fa-house" title="Inici"></i>
                </a>
            </div>

        </div>
    </nav>
    <main class="flex-grow-1">