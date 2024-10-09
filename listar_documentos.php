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

$usuario_id = $_SESSION["user_id"];
$query = "SELECT id, titulo, fecha_creacion FROM documentos WHERE usuario_id = $1 ORDER BY fecha_creacion DESC";
$result = pg_query_params($conn, $query, array($usuario_id));

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Documentos</title>
</head>
<body>
    <h1>Mis Documentos</h1>
    <a href="crear_documento.html">Crear Nuevo Documento</a>
    <table border="1">
        <tr>
            <th>Título</th>
            <th>Fecha de Creación</th>
        </tr>
        <?php
        while ($row = pg_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["titulo"]) . "</td>";
            echo "<td>" . $row["fecha_creacion"] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
pg_close($conn);
?>
