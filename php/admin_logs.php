<?php
require 'vendor/autoload.php';
$db         = require 'connexion_mongo.php';
$collection = $db->logs;

// Dia seleccionat (per defecte: avui)
$diaSeleccionat = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

// Rang de timestamps del dia seleccionat
$iniciDia = new MongoDB\BSON\UTCDateTime(strtotime($diaSeleccionat . ' 00:00:00') * 1000);
$fiDia    = new MongoDB\BSON\UTCDateTime(strtotime($diaSeleccionat . ' 23:59:59') * 1000);

// Total d'accessos
$total = $collection->countDocuments([]);

// Top 5 pàgines més visitades
$pagines = iterator_to_array($collection->aggregate([
    ['$group' => ['_id' => '$url', 'total' => ['$sum' => 1]]],
    ['$sort'  => ['total' => -1]],
    ['$limit' => 5],
]));

// Accessos per dia (últims 14 dies, per al gràfic)
$perDia = iterator_to_array($collection->aggregate([
    ['$group' => [
        '_id'   => ['$dateToString' => ['format' => '%d-%m-%Y', 'date' => '$timestamp']],
        'total' => ['$sum' => 1],
    ]],
    ['$sort'  => ['_id' => 1]],
    ['$limit' => 14],
]));

// Només els 10 últims accessos del dia seleccionat
$ultims = iterator_to_array($collection->find(
    ['timestamp' => ['$gte' => $iniciDia, '$lte' => $fiDia]],
    ['sort' => ['timestamp' => -1], 'limit' => 10]
));

// Última visita
$last = $collection->findOne([], ['sort' => ['timestamp' => -1]]);

// Dades per al gràfic
$diesLabels = array_map(fn($d) => $d['_id'],   $perDia);
$diesDades  = array_map(fn($d) => $d['total'], $perDia);

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

        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Accessos per dia</h6>
                    <canvas id="graficDies" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Pàgines més visitades</h6>
                    <?php
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

        <!-- Filtre de data: al canviar recarrega la pàgina per $_GET['data'] -->
        <input
            type="date"
            id="filtreData"
            class="form-control form-control-sm"
            style="width: auto;"
            value="<?= htmlspecialchars($diaSeleccionat) ?>"
            onchange="window.location.href = '?data=' + this.value"
        >

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
            <h6 class="card-title text-muted">10 últims accessos</h6>

            <div class="table-responsive" style="font-size: 0.80em;">
                <table class="table table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th class="bg-primary text-white">Dia i Hora</th>
                            <th class="bg-primary text-white">Mètode</th>
                            <th class="bg-primary text-white">URL</th>
                            <th class="bg-primary text-white">IP</th>
                        </tr>
                    </thead>
                    <tbody id="taulaCos">
                        <!-- Les files es generen amb JavaScript -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- Fi taula -->

</div><!-- Fi container -->


<!-- ====== JAVASCRIPT ====== -->

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
            scales: { y: { beginAtZero: true } }
        }
    });
</script>

<script>
    // PHP passa només els 10 últims logs del dia seleccionat
    const totsElsLogs = <?= json_encode(array_map(fn($doc) => [
        'timestamp' => $doc['timestamp']->toDateTime()->format('d-m-Y H:i:s'),
        'method'    => $doc['method'] ?? '-',
        'url'       => $doc['url'] ?? '-',
        'ip'        => $doc['ip'] ?? '-',
    ], $ultims)) ?>;

    let logsFiltrats = [...totsElsLogs];

    // Mostra els logs filtrats a la taula
    function mostrarTaula() {
        const cos = document.getElementById('taulaCos');
        cos.innerHTML = '';

        if (logsFiltrats.length === 0) {
            cos.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">No hi ha accessos.</td></tr>';
            return;
        }

        logsFiltrats.forEach(function(log) {
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
    }

    // Filtra els logs segons mètode i pàgina (la data ja la gestiona PHP)
    function filtrar() {
        const metode = document.getElementById('filtreMetode').value.toUpperCase();
        const pagina = document.getElementById('filtrePagina').value.toLowerCase();

        logsFiltrats = totsElsLogs.filter(function(log) {
            const okMetode = !metode || log.method.toUpperCase() === metode;
            const okPagina = !pagina || log.url.toLowerCase().includes(pagina);
            return okMetode && okPagina;
        });

        mostrarTaula();
    }

    document.getElementById('filtreMetode').addEventListener('change', filtrar);
    document.getElementById('filtrePagina').addEventListener('change', filtrar);

    mostrarTaula();
</script>

<?php include_once "fotter.php"; ?>