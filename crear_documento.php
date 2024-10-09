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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $contenido = $_POST["contenido"];
    $usuario_id = $_SESSION["user_id"];

    $query = "INSERT INTO documentos (titulo, contenido, usuario_id) VALUES ($1, $2, $3) RETURNING id";
    $result = pg_query_params($conn, $query, array($titulo, $contenido, $usuario_id));

    if ($row = pg_fetch_assoc($result)) {
        $documento_id = $row["id"];
        echo "Documento creado exitosamente con ID: " . $documento_id;
    } else {
        echo "Error al crear documento: " . pg_last_error($conn);
    }
}

pg_close($conn);
?>
