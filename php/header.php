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

        .navbar-brand img {
            height: 40px;
            transition: height 0.3s ease;
        }

        @media (max-width: 575px) {
            .navbar-brand img {
                height: 35px;
            }

            .navbar-brand {
                margin-right: 5px;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-light bg-light border-bottom sticky-top mb-4" aria-label="Navegació principal">
        <div class="container-fluid gap-2">

            <!-- Logo -->
            <a class="navbar-brand flex-shrink-0" href="index.php" aria-label="Inici - Gestor d'incidències">
                <img src="resources/Logoinsti_amb_lletres.png" alt="Logo Institut Pedralbes" height="40">
            </a>

            <!-- Mòbil -->
            <div class="d-flex gap-2 d-md-none ms-auto align-items-center">
                <form action="detall_incidencia.php" method="GET" class="input-group" style="max-width: 160px;" role="search">
                    <span class="input-group-text" aria-hidden="true">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="number" name="idBusca" class="form-control"
                        placeholder="Id..."
                        aria-label="Cerca per ID d'incidència"
                        min="1">
                    <button class="btn btn-primary" type="submit" aria-label="Cercar incidència">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    </button>
                </form>
                <a href="admin_logs.php" class="btn btn-outline-primary" aria-label="Estadístiques de logs">
                    <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                </a>
                <a href="index.php" class="btn btn-outline-primary" aria-label="Inici">
                    <i class="fa-solid fa-house" aria-hidden="true"></i>
                </a>
            </div>

            <!-- Escriptori -->
            <form action="detall_incidencia.php" method="GET" class="input-group d-none d-md-flex position-absolute start-50 translate-middle-x" style="max-width: 360px;" role="search">
                <span class="input-group-text" aria-hidden="true">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="number" name="idBusca" class="form-control"
                    placeholder="Id incidencia..."
                    aria-label="Cerca per ID d'incidència"
                    min="1">
                <button class="btn btn-primary" type="submit" aria-label="Cercar incidència">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                </button>
            </form>

            <div class="d-none d-md-flex gap-1 ms-auto">
                <a href="admin_logs.php" class="btn btn-outline-primary" title="Estadístiques de logs">
                    <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                    <span class="visually-hidden">Estadístiques de logs</span>
                </a>
                <a href="index.php" class="btn btn-outline-primary" title="Inici">
                    <i class="fa-solid fa-house" aria-hidden="true"></i>
                    <span class="visually-hidden">Inici</span>
                </a>
            </div>

        </div>
    </nav>
    <main class="flex-grow-1">