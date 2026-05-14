<?php
// Connexió a MongoDB i recollida de dades
require 'vendor/autoload.php';
$db         = require 'connexion_mongo.php';
$collection = $db->logs;

// Total d'accessos
$total = $collection->countDocuments([]);

// Top 5 pàgines més visitades
$pagines = iterator_to_array($collection->aggregate([
    ['$group' => ['_id' => '$url', 'total' => ['$sum' => 1]]],
    ['$sort'  => ['total' => -1]],
    ['$limit' => 5],
]));

// Accessos per dia (últims 14 dies)
$perDia = iterator_to_array($collection->aggregate([
    ['$group' => [
        '_id'   => ['$dateToString' => ['format' => '%d-%m-%Y', 'date' => '$timestamp']],
        'total' => ['$sum' => 1],
    ]],
    ['$sort'  => ['_id' => 1]],
    ['$limit' => 14],
]));

// Llista de tots els accessos (del més nou al més antic)
$ultims = iterator_to_array($collection->find([], ['sort' => ['timestamp' => -1]]));

// Última visita
$last = $collection->findOne([], ['sort' => ['timestamp' => -1]]);

// Preparar dades per al gràfic
$diesLabels = array_map(fn($d) => $d['_id'],    $perDia);
$diesDades  = array_map(fn($d) => $d['total'],  $perDia);

include_once "header.php";
?>

<div class="container mt-4">
    <h4 class="mb-4">
        <i class="fa-solid fa-chart-line me-2"></i>Panell d'accessos
    </h4>

    <!-- ====== TARGETES DE RESUM ====== -->
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="text-muted small">Total accessos</div>
                    <div class="fs-2 fw-bold text-primary"><?= $total ?></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="text-muted small">Pàgines úniques</div>
                    <div class="fs-2 fw-bold text-primary"><?= count($pagines) ?></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="text-muted small">Dies amb dades</div>
                    <div class="fs-2 fw-bold text-primary"><?= count($perDia) ?></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="text-muted small">Última visita</div>
                    <div class="fs-2 fw-bold text-primary">
                        <?= $last ? $last['timestamp']->toDateTime()->format('H:i:s') : '-' ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Fi targetes -->


    <!-- ====== GRÀFIC + PÀGINES MÉS VISITADES ====== -->
    <div class="row g-3 mb-4">

        <!-- Gràfic de línies -->
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Accessos per dia</h6>
                    <canvas id="graficDies" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Barres de progrés -->
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Pàgines més visitades</h6>

                    <?php
                    // Valor màxim per calcular el percentatge de cada barra
                    $maxVisites = count($pagines) > 0 ? $pagines[0]['total'] : 1;

                    foreach ($pagines as $pagina):
                        $percentatge = round(($pagina['total'] / $maxVisites) * 100);
                    ?>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span><?= htmlspecialchars($pagina['_id'] ?? '-') ?></span>
                                <span class="fw-bold"><?= $pagina['total'] ?></span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" style="width: <?= $percentatge ?>%;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

    </div>
    <!-- Fi gràfic -->


    <!-- ====== FILTRES ====== -->
    <div class="d-flex gap-2 mb-3 flex-wrap">

        <input type="date" id="filtreData" class="form-control form-control-sm" style="width: auto;">

        <select id="filtreMetode" class="form-select form-select-sm" style="width: auto;">
            <option value="">Tots els mètodes</option>
            <option value="GET">GET</option>
            <option value="POST">POST</option>
        </select>

        <select id="filtrePagina" class="form-select form-select-sm" style="width: auto;">
            <option value="">Totes les pàgines</option>
            <?php foreach ($pagines as $pagina): ?>
                <option value="<?= htmlspecialchars($pagina['_id'] ?? '-') ?>">
                    <?= htmlspecialchars($pagina['_id'] ?? '-') ?>
                </option>
            <?php endforeach; ?>
        </select>

    </div>
    <!-- Fi filtres -->


    <!-- ====== TAULA D'ACCESSOS ====== -->
    <div class="card mb-5">
        <div class="card-body">
            <h6 class="card-title text-muted">Accessos</h6>

            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <thead>
                        <tr class="table table-striped table-hover table-sm align-middle" style="font-size: 0.72em; min-width: 700px;">
                            <th class="bg-primary text-white p-2 border-primary">Hora</th>
                            <th class="bg-primary text-white p-2 border-primary">Mètode</th>
                            <th class="bg-primary text-white p-2 border-primary">URL</th>
                            <th class="bg-primary text-white p-2 border-primary">IP</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.72em;" id="taulaCos">
                        <!-- Les files es generen amb JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Botons de paginació -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <button class="btn btn-sm btn-primary" id="btnAnterior" onclick="canviarPagina(-1)">
                    <i class="fa-solid fa-chevron-left"></i> Anterior
                </button>
                <span id="infoPagina" class="text-muted small"></span>
                <button class="btn btn-sm btn-primary" id="btnSeguent" onclick="canviarPagina(1)">
                    Següent <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

        </div>
    </div>
    <!-- Fi taula -->

</div><!-- Fi container -->


<!-- ====== JAVASCRIPT ====== -->

<!-- Gràfic de línies amb Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

<!-- Taula amb filtres i paginació -->
<script>
    // Dades de tots els logs (PHP les passa a JavaScript)
    const totsElsLogs = <?= json_encode(array_map(fn($doc) => [
        'timestamp' => $doc['timestamp']->toDateTime()->format('d-m-Y H:i:s'),
        'method'    => $doc['method'] ?? '-',
        'url'       => $doc['url'] ?? '-',
        'ip'        => $doc['ip'] ?? '-',
    ], $ultims)) ?>;

    let logsFiltrats = [...totsElsLogs]; // Còpia dels logs (es modifica en filtrar)
    let paginaActual = 0;
    const REGISTRES_PER_PAGINA = 10;

    // Mostra les files de la pàgina actual a la taula
    function mostrarTaula() {
        const inici = paginaActual * REGISTRES_PER_PAGINA;
        const fi    = inici + REGISTRES_PER_PAGINA;
        const logsDelaPagina = logsFiltrats.slice(inici, fi);

        const cos = document.getElementById('taulaCos');
        cos.innerHTML = '';

        logsDelaPagina.forEach(function(log) {
            const badgeColor = log.method === 'POST' ? 'bg-primary' : 'bg-success';
            cos.innerHTML += `
                <tr>
                    <td>${log.timestamp}</td>
                    <td><span class="badge ${badgeColor}">${log.method}</span></td>
                    <td>${log.url}</td>
                    <td class="font-monospace small">${log.ip}</td>
                </tr>
            `;
        });

        // Actualitzar el text i els botons de paginació
        const totalPagines = Math.ceil(logsFiltrats.length / REGISTRES_PER_PAGINA);
        document.getElementById('infoPagina').textContent =
            `Pàgina ${paginaActual + 1} de ${totalPagines} (${logsFiltrats.length} registres)`;

        document.getElementById('btnAnterior').disabled = paginaActual === 0;
        document.getElementById('btnSeguent').disabled  = paginaActual >= totalPagines - 1;
    }

    // Avança o retrocedeix de pàgina (delta = +1 o -1)
    function canviarPagina(delta) {
        const totalPagines = Math.ceil(logsFiltrats.length / REGISTRES_PER_PAGINA);
        paginaActual = paginaActual + delta;

        // Assegurar que no surti dels límits
        if (paginaActual < 0) paginaActual = 0;
        if (paginaActual >= totalPagines) paginaActual = totalPagines - 1;

        mostrarTaula();
    }

    // Filtra els logs segons els valors dels selectors
    function filtrar() {
        const data    = document.getElementById('filtreData').value;
        const metode  = document.getElementById('filtreMetode').value.toUpperCase();
        const pagina  = document.getElementById('filtrePagina').value.toLowerCase();

        logsFiltrats = totsElsLogs.filter(function(log) {
            // Comparar data (el filtre dóna YYYY-MM-DD, el log té DD-MM-YYYY)
            const dataLog    = log.timestamp.substring(0, 10); // "DD-MM-YYYY"
            const dataFiltro = data ? data.split('-').reverse().join('-') : '';
            const okData    = !data   || dataLog === dataFiltro;
            const okMetode  = !metode || log.method.toUpperCase() === metode;
            const okPagina  = !pagina || log.url.toLowerCase().includes(pagina);

            return okData && okMetode && okPagina;
        });

        paginaActual = 0; // Tornar a la primera pàgina en canviar el filtre
        mostrarTaula();
    }

    // Escoltar canvis als filtres
    document.getElementById('filtreData').addEventListener('change', filtrar);
    document.getElementById('filtreMetode').addEventListener('change', filtrar);
    document.getElementById('filtrePagina').addEventListener('change', filtrar);

    // Mostrar la taula per primera vegada
    mostrarTaula();
</script>

<?php include_once "fotter.php"; ?>