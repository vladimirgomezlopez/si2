<?php
$host = "localhost";
$dbname = "nombre_de_tu_base_de_datos";
$user = "tu_usuario";
$password = "tu_contraseña";

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

$token = isset($_GET['token']) ? $_GET['token'] : '';

if (empty($token)) {
    die("Token de documento no válido");
}

$query = "SELECT titulo, contenido FROM documentos WHERE enlace_compartido = $1";
$result = pg_query_params($conn, $query, array($token));

if (!$result || pg_num_rows($result) === 0) {
    die("Documento no encontrado");
}

$documento = pg_fetch_assoc($result);

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($documento["titulo"]); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($documento["titulo"]); ?></h1>
    <div id="contenido-documento">
        <?php echo nl2br(htmlspecialchars($documento["contenido"])); ?>
    </div>
</body>
</html>
