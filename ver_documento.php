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

$query = "SELECT titulo, contenido FROM documentos WHERE id = $1";
$result = pg_query_params($conn, $query, array($documento_id));

if (!$result || pg_num_rows($result) === 0) {
    die("Documento no encontrado");
}

$documento = pg_fetch_assoc($result);

// Obtener comentarios
$query_comentarios = "SELECT c.id, c.contenido, c.fecha_creacion, u.nombre_usuario 
                       FROM comentarios c 
                       JOIN usuarios u ON c.usuario_id = u.id 
                       WHERE c.documento_id = $1 
                       ORDER BY c.fecha_creacion DESC";
$result_comentarios = pg_query_params($conn, $query_comentarios, array($documento_id));

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($documento["titulo"]); ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="comentarios.js"></script>
</head>
<body>
    <h1><?php echo htmlspecialchars($documento["titulo"]); ?></h1>
    <div id="contenido-documento">
        <?php echo nl2br(htmlspecialchars($documento["contenido"])); ?>
    </div>
    
    <h2>Comentarios</h2>
    <div id="lista-comentarios">
        <?php
        while ($comentario = pg_fetch_assoc($result_comentarios)) {
            echo "<div class='comentario'>";
            echo "<p><strong>" . htmlspecialchars($comentario["nombre_usuario"]) . "</strong> - " . $comentario["fecha_creacion"] . "</p>";
            echo "<p>" . nl2br(htmlspecialchars($comentario["contenido"])) . "</p>";
            echo "</div>";
        }
        ?>
    </div>
    
    <h3>Añadir comentario</h3>
    <form id="form-comentario">
        <input type="hidden" name="documento_id" value="<?php echo $documento_id; ?>">
        <textarea name="contenido" rows="4" cols="50" required></textarea><br>
        <input type="submit" value="Enviar comentario">
    </form>
</body>
</html>
