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
        '_id'   => ['$dateToString' => ['format' => '%Y-%m-%d', 'date' => '$timestamp']],
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

<div class="container">
    <h4 class="mb-4"><i class="fa-solid fa-chart-line me-2"></i>Panell d'accessos</h4>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total accessos</div>
                    <div class="fs-2 fw-bold text-primary"><?= $total ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Pàgines úniques</div>
                    <div class="fs-2 fw-bold text-primary"><?= count($pagines) ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Dies amb dades</div>
                    <div class="fs-2 fw-bold text-primary"><?= count($perDia) ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Última visita</div>
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
                    <h6 class="card-title text-muted">Accessos per dia</h6>
                    <canvas id="graficDies"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted">Pàgines més visitades</h6>
                    <?php
                    $maxVisites = count($pagines) > 0 ? $pagines[0]['total'] : 1;
                    foreach ($pagines as $p):
                        $pct = round(($p['total'] / $maxVisites) * 100);
                    ?>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-truncate me-2" style="max-width:200px" title="<?= htmlspecialchars($p['_id'] ?? '-') ?>">
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

    <div class="card mb-4">
        <div class="card-body">
            <h6 class="card-title text-muted">Últims accessos</h6>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="bg-primary text-white p-2 border-primary">Hora</th>
                            <th class="bg-primary text-white p-2 border-primary">Mètode</th>
                            <th class="bg-primary text-white p-2 border-primary">URL</th>
                            <th class="bg-primary text-white p-2 border-primary d-none d-md-table-cell">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($ultims as $doc): ?>
                        <tr>
                            <td class="text-nowrap"><?= $doc['timestamp']->toDateTime()->format('Y-m-d H:i:s') ?></td>
                            <td>
                                <span class="badge <?= ($doc['method'] ?? 'GET') === 'POST' ? 'bg-primary' : 'bg-success' ?>">
                                    <?= htmlspecialchars($doc['method'] ?? '-') ?>
                                </span>
                            </td>
                            <td class="text-truncate" style="max-width:200px"><?= htmlspecialchars($doc['url'] ?? '-') ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars($doc['ip'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
            pointBackgroundColor: '#0d6efd',
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: '#f0f0f0' } },
            y: { grid: { color: '#f0f0f0' }, beginAtZero: true }
        }
    }
});
</script>

<?php include_once "fotter.php"; ?>