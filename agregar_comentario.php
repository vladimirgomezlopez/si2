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

$documento_id = isset($_POST['documento_id']) ? intval($_POST['documento_id']) : 0;
$contenido = isset($_POST['contenido']) ? $_POST['contenido'] : '';
$usuario_id = $_SESSION["user_id"];

if ($documento_id === 0 || empty($contenido)) {
    http_response_code(400);
    exit("Datos de comentario inválidos");
}

$query = "INSERT INTO comentarios (documento_id, usuario_id, contenido) VALUES ($1, $2, $3) RETURNING id, fecha_creacion";
$result = pg_query_params($conn, $query, array($documento_id, $usuario_id, $contenido));

if ($result) {
    $comentario = pg_fetch_assoc($result);
    $query_usuario = "SELECT nombre_usuario FROM usuarios WHERE id = $1";
    $result_usuario = pg_query_params($conn, $query_usuario, array($usuario_id));
    $usuario = pg_fetch_assoc($result_usuario);
    
    $respuesta = array(
        'id' => $comentario['id'],
        'contenido' => $contenido,
        'fecha_creacion' => $comentario['fecha_creacion'],
        'nombre_usuario' => $usuario['nombre_usuario']
    );
    
    echo json_encode($respuesta);
} else {
    http_response_code(500);
    echo "Error al guardar el comentario: " . pg_last_error($conn);
}

pg_close($conn);
?>
