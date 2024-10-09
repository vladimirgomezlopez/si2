<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$host = "localhost";
$dbname = "nombre_de_tu_base_de_datos";
$user = "tu_usuario";
$password = "tu_contraseña";

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

$documento_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($documento_id === 0) {
    die("ID de documento no válido");
}

$query = "SELECT titulo, enlace_compartido FROM documentos WHERE id = $1 AND usuario_id = $2";
$result = pg_query_params($conn, $query, array($documento_id, $_SESSION["user_id"]));

if (!$result || pg_num_rows($result) === 0) {
    die("Documento no encontrado o no tienes permiso para compartirlo");
}

$documento = pg_fetch_assoc($result);

if (!isset($documento['enlace_compartido']) || empty($documento['enlace_compartido'])) {
    $enlace_compartido = bin2hex(random_bytes(16));
    $update_query = "UPDATE documentos SET enlace_compartido = $1 WHERE id = $2";
    $update_result = pg_query_params($conn, $update_query, array($enlace_compartido, $documento_id));
    
    if (!$update_result) {
        die("Error al generar el enlace compartido: " . pg_last_error($conn));
    }
    
    $documento['enlace_compartido'] = $enlace_compartido;
}

$enlace_completo = "http://" . $_SERVER['HTTP_HOST'] . "/ver_documento_compartido.php?token=" . $documento['enlace_compartido'];

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartir Documento</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="compartir.js"></script>
</head>
<body>
    <h1>Compartir Documento: <?php echo htmlspecialchars($documento["titulo"]); ?></h1>
    <p>Enlace para compartir:</p>
    <input type="text" id="enlace-compartido" value="<?php echo $enlace_completo; ?>" readonly>
    <button id="copiar-enlace">Copiar enlace</button>
    <p id="mensaje-copiado" style="display: none;">Enlace copiado al portapapeles</p>
</body>
</html>
