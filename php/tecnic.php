<?php
include_once "header.php";
?>

<div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 60vh;">
    
<i class="fas fa-regular fa-user fa-3x"></i>
<h2 class="mb-4">Selecciona el teu usuari</h2>
    <div class="d-grid gap-3 w-100" style= "max-width: 300px;">
        <a href="llistar_incidencies_tecnic.php?tecnic=Tècnic+1" class="btn btn-primary btn-lg px-5 shadow">
            Tècnic 1
        </a>
        <a href="llistar_incidencies_tecnic.php?tecnic=Tècnic+2" class="btn btn-primary btn-lg px-5 shadow">
            Tècnic 2
        </a>
    </div>
</div>

<?php
include_once "fotter.php";
?>