<?php 

$pdo = new PDO(
	'mysql:host=localhost;dbname=inventario;charset=utf8mb4',
	'inventario_user',
	'CLAVE_AQUI',
	[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);
