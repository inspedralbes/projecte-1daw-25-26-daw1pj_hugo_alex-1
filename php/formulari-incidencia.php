<?php
require_once 'connexio.php';
$tipus = $conn->query("select idTipo, nombre from TIPO");
$departaments = $conn->query("select idDepartamento, nombre from DEPARTAMENTO");
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
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
    </form>

</body>
</html>