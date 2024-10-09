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

$query = "SELECT titulo, contenido FROM documentos WHERE id = $1 AND usuario_id = $2";
$result = pg_query_params($conn, $query, array($documento_id, $_SESSION["user_id"]));

if (!$result || pg_num_rows($result) === 0) {
    die("Documento no encontrado o no tienes permiso para editarlo");
}

$documento = pg_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_contenido = $_POST["contenido"];
    $update_query = "UPDATE documentos SET contenido = $1 WHERE id = $2 AND usuario_id = $3";
    $update_result = pg_query_params($conn, $update_query, array($nuevo_contenido, $documento_id, $_SESSION["user_id"]));
    
    if ($update_result) {
        echo "<p>Documento actualizado exitosamente.</p>";
        $documento["contenido"] = $nuevo_contenido;
    } else {
        echo "<p>Error al actualizar el documento: " . pg_last_error($conn) . "</p>";
    }
}

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Documento</title>
</head>
<body>
    <h1>Editar Documento: <?php echo htmlspecialchars($documento["titulo"]); ?></h1>
    <form action="" method="POST">
        <textarea name="contenido" rows="20" cols="80"><?php echo htmlspecialchars($documento["contenido"]); ?></textarea><br><br>
        <input type="submit" value="Guardar Cambios">
    </form>
    <a href="listar_documentos.php">Volver a la lista de documentos</a>
</body>
</html>
