<?php
require 'vendor/autoload.php';
$db = require 'connexion_mongo.php';
$collection = $db->logs;

$total = $collection->countDocuments([]);

$pagines = iterator_to_array($collection->aggregate([
    ['$group' => ['_id' => '$url', 'total' => ['$sum' => 1]]],
    ['$sort'  => ['total' => -1]],
    ['$limit' => 5],
]));

$perDia = iterator_to_array($collection->aggregate([
    ['$group' => [
        '_id'   => ['$dateToString' => ['format' => '%d-%m-%Y', 'date' => '$timestamp']],
        'total' => ['$sum' => 1],
    ]],
    ['$sort'  => ['_id' => 1]],
    ['$limit' => 14],
]));

$diesLabels = array_map(fn($d) => $d['_id'], $perDia);
$diesDades  = array_map(fn($d) => $d['total'], $perDia);

$ultims = $collection->find([], [
    'sort'  => ['timestamp' => -1],
    'limit' => 10,
]);

$last = $collection->findOne([], ['sort' => ['timestamp' => -1]]);

include_once "header.php";
?>

<div class="container px-3 mt-4">
    <h4 class="mb-4"><i class="fa-solid fa-chart-line me-2"></i>Panell d'accessos</h4>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center h-100">
                <div class="card-body p-2 p-md-3">
                    <div class="small mb-1" style="color: #555;">Total accessos</div>
                    <div class="fs-2 fw-bold text-primary"><?= $total ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center h-100">
                <div class="card-body p-2 p-md-3">
                    <div class="small mb-1" style="color: #555;">Pàgines úniques</div>
                    <div class="fs-2 fw-bold text-primary"><?= count($pagines) ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center h-100">
                <div class="card-body p-2 p-md-3">
                    <div class="small mb-1" style="color: #555;">Dies amb dades</div>
                    <div class="fs-2 fw-bold text-primary"><?= count($perDia) ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center h-100">
                <div class="card-body p-2 p-md-3">
                    <div class="small mb-1" style="color: #555;">Última visita</div>
                    <div class="fs-5 fw-bold text-primary">
                        <?= $last ? $last['timestamp']->toDateTime()->format('H:i:s') : '-' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-8">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title" style="color: #555;">Accessos per dia</h6>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="graficDies"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title" style="color: #555;">Pàgines més visitades</h6>
                    <?php
                    $maxVisites = count($pagines) > 0 ? $pagines[0]['total'] : 1;
                    foreach ($pagines as $p):
                        $pct = round(($p['total'] / $maxVisites) * 100);
                    ?>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-truncate me-2" style="max-width:180px" title="<?= htmlspecialchars($p['_id'] ?? '-') ?>">
                                    <?= htmlspecialchars($p['_id'] ?? '-') ?>
                                </span>
                                <span class="fw-bold"><?= $p['total'] ?></span>
                            </div>
                            <div class="progress" style="height:6px">
                                <div class="progress-bar" style="width:<?= $pct ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex grap-2 mb-3 flex-wrap">
        <input type="date" id="filtreData" class="form-control-sm" style="width:auto;">
        <select id="filtreMetode" class="form-select form-select-sm" style="width:auto;">
            <option value="">Tots els mètodes</option>
            <option value="GET">GET</option>
            <option value="POST">POST</option>
        </select>
        <select id="filtrePagina" class="form-select form-select-sm" style="width:auto;">
            <option value="">Totes les pàgines</option>
            <?php foreach ($pagines as $p): ?>
                <option value="<?= htmlspecialchars($p['_id'] ?? '-')?>">
                    <?= htmlspecialchars($p['_id'] ?? '-')?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="card mb-5">
        <div class="card-body">
            <h6 class="card-title" style="color: #555;">Últims accessos</h6>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm align-middle mb-0" style="font-size: 0.85em;">
                    <thead>
                        <tr>
                            <th class="bg-primary text-white p-2 border-primary">Hora</th>
                            <th class="bg-primary text-white p-2 border-primary">Mètode</th>
                            <th class="bg-primary text-white p-2 border-primary">URL</th>
                            <th class="bg-primary text-white p-2 border-primary">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultims as $doc): ?>
                            <tr>
                                <td class="text-nowrap p-2"><?= $doc['timestamp']->toDateTime()->format('d-m-Y H:i:s') ?></td>
                                <td class="p-2">
                                    <span class="badge <?= ($doc['method'] ?? 'GET') === 'POST' ? 'bg-primary' : 'bg-success' ?>">
                                        <?= htmlspecialchars($doc['method'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="text-truncate p-2" style="max-width:150px"><?= htmlspecialchars($doc['url'] ?? '-') ?></td>
                                <td class="p-2 font-monospace small"><?= htmlspecialchars($doc['ip'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Eliminar cualquier instancia previa para evitar errores de renderizado
        let chartStatus = Chart.getChart("graficDies");
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }

        new Chart(document.getElementById('graficDies'), {
            type: 'line',
            data: {
                labels: <?= json_encode($diesLabels) ?>,
                datasets: [{
                    label: 'Accessos',
                    data: <?= json_encode($diesDades) ?>,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#0d6efd',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Fundamental para que no crezca sola
                resizeDelay: 200, // Evita el bucle de redimensionamiento
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: '#f0f0f0'
                        },
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: '#f0f0f0'
                        },
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script>
        document.getElementById('filtreData').addEventListener('change', filtrar);
        document.getElementById('filtreMetode').addEventListener('change', filtrar);
        document.getElementById('filtrePagina').addEventListener('change', filtrar);

        function filtrar() {
            const data = document.getElementById('filtreData').value;
            const metode = document.getElementById('filtreMetode').value.toUpperCase();
            const pagina = document.getElementById('filtrePagina').value.toLowerCase();
        
            document.querySelectorAll('tbody tr').forEach(function (fila) {
                const textFila = fila.textContent.toLowerCase();
                const horaCell = fila.cells[0].textContent.trim();
                const mostrarData = data === '' || horaCell.startsWith(data.split('-').reverse().join('-'));
                const mostrarMetode = metode === '' || textFila.includes(metode.toLowerCase());
                const mostrarPagina = pagina === '' || textFila.includes(pagina); 
                
                fila.style.display = (mostrarData && mostrarMetode && mostrarPagina) ? '' : 'none';
            });
        }
    </script>

    <?php include_once "fotter.php"; ?>