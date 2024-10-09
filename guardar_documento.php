<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(403);
    exit("Acceso no autorizado");
}

$host = "localhost";
$dbname = "nombre_de_tu_base_de_datos";
$user = "tu_usuario";
$password = "tu_contraseña";

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    http_response_code(500);
    exit("Error de conexión: " . pg_last_error());
}

$documento_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$contenido = isset($_POST['contenido']) ? $_POST['contenido'] : '';

if ($documento_id === 0) {
    http_response_code(400);
    exit("ID de documento no válido");
}

$query = "UPDATE documentos SET contenido = $1 WHERE id = $2 AND usuario_id = $3";
$result = pg_query_params($conn, $query, array($contenido, $documento_id, $_SESSION["user_id"]));

if ($result) {
    echo "Documento guardado exitosamente";
} else {
    http_response_code(500);
    echo "Error al guardar el documento: " . pg_last_error($conn);
}

pg_close($conn);
?>
