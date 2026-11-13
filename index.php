<?php

require __DIR__ . '/config.php';
function h($t) {return htmlspecialchars($t ?? '', ENT_QUOTES, 'UTF-8'); }

if (isset($_POST['eliminar'])) {
	$pdo->prepare('DELETE FROM equipos WHERE id=?')->execute([$_POST['id']]);
	header('Location: index.php'); exit;
}

if (isset($_POST['guardar'])) {
	$d = [$_POST['nombre'], $_POST['tipo'], $_POST['marca'],
	$_POST['ubicacion'], $_POST['estado']];
	if (!empty($_POST['id'])) {
		$d[] = $_POST['id'];
		$st = $pdo->prepare('UPDATE equipos SET nombre=?, tipo=?, marca=?, ubicacion=?, estado=? WHERE id=?');
	} else {
		$st = $pdo->prepare('INSERT INTO equipos (nombre, tipo, marca, ubicacion, estado) VALUES (?,?,?,?,?)');
	}
	$st->execute($d);
	header('Location: index.php'); exit;
}

$e = ['id'=>'','nombre'=>'','tipo'=>'','marca'=>'','ubicacion'=>'','estado'=>'Activo'];
if (isset($_GET['editar'])) {
	$st = $pdo->prepare('SELECT * FROM equipos WHERE id=?');
	$st->execute([$_GET['editar']]);
	$e = $st->fetch() ?: $e;
}
$equipos = $pdo->query('SELECT * FROM equipos ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inventario de equipos</title>
<style>
body{font-family:sans-serif;max-width:800px;margin:20px auto}
table{border-collapse:collapse;width:100%;margin-top:15px}
th,td{dorder:1px solid #999;padding:6px
</style>
</head>
<body>
<h1>Inventario de equipos - Version 2.</h1>
<h2><?= $e['id'] ? 'Modificar equipo' : 'Registrar equipos' ?></h2>
<form method="post">
<input type="hidden" name="id" value="<?= h($e['id']) ?>">
<input name="nombre" placeholder="Nombre" value="<?= h($e['nombre']) ?>" required><br>
<input name="tipo" placeholder="Tipo" value="<?= h($e['tipo']) ?>" required><br>
<input name="marca" placeholder="Marca" value"<?= h($e['marca']) ?>"><br>
<input name="ubicacion" placeholder="Ubicacion" value="<?= h($e['ubicacion']) ?>"><br>
<select name="estado">
<?php foreach  (['Activo', 'En reparacion','Baja'] as $o): ?>
<option <?= $e['estado']===$o ? 'selected' : '' ?>><?= $o ?></option>
<?php endforeach; ?>
</select><br>
<button name="guardar">Guardar</button>
</form>
<h2>Equipos registrados</h2>
<table>
<tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Marca</th><th>Ubicacion</th><th>Estado</th><th>Acciones</th></tr>
<?php foreach ($equipos as $q): ?>
<tr>
<td><?= $q['id'] ?></td>
<td><?= h($q['nombre']) ?></td>
<td><?= h($q['tipo']) ?></td>
<td><?= h($q['marca']) ?></td>
<td><?= h($q['ubicacion']) ?></td>
<td><?= h($q['estado']) ?></td>
<td>
<a href="?editar=<?= $q['id']; ?>">Editar</a>
<form method="post" style="display:inline">
<input type="hidden" name="id" value="<?= $q['id'] ?>">
<button name="eliminar">Eliminar</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>



