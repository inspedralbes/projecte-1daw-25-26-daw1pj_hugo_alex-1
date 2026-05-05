<?php
require_once 'connexio.php';
$tipus = $conn->query("select idTipo, nombre from TIPO");
$departaments = $conn->query("select idDepartamento, nombre from DEPARTAMENTO");
include_once 'header.php';
?>
    <title>Nova Incidència</title>
</head>
<body>
    <h1>Nova Incidència</h1>

    <form method="POST" action="crear_incidencia.php">
        <fieldset>
            <legend>Nova Incidència</legend>

            <label for="idTipus">Tipus:</label>
            <select name="idTipus" id="idTipus">
                <?php while($fila = $tipus->fetch_assoc()): ?>
                    <option value="<?= $fila['idTipo'] ?>"><?= $fila['nombre'] ?></option>
                <?php endwhile; ?>
                <option value="altres">Altres</option>
            </select>
            <label for="idDepartament">Departament:</label>
                    <select name="idDepartament" id="idDepartament">
                <?php while($fila = $departaments->fetch_assoc()): ?>
                    <option value="<?= $fila['idDepartamento'] ?>"><?= $fila['nombre'] ?></option>
                <?php endwhile; ?>
            </select>
            <label for = "descripcio"> Descripció:</label>
            <textarea name="descripcio" id="descripcio" rows = "4" cols="50"></textarea>
            <input type="submit" value="Crear Incidència">
        </fieldset>
        <a href="llistar_incidencies_usuari.php" class="btn btn-primary btn-lg px-5 shadow">
                        <div bclass="bi bi-collection-play">Llistat incidencies</div>
                    </a>
    </form>
<?php
include_once 'fotter.php';
?>