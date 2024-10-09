<?php
session_start();

$host = "localhost";
$dbname = "nombre_de_tu_base_de_datos";
$user = "tu_usuario";
$password = "tu_contraseña";

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT id, nombre_usuario, contrasena FROM usuarios WHERE nombre_usuario = $1";
    $result = pg_query_params($conn, $query, array($username));

    if ($row = pg_fetch_assoc($result)) {
        if (password_verify($password, $row["contrasena"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["nombre_usuario"];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de inicio de sesión</title>
</head>
<body>
    <h1>Error de inicio de sesión</h1>
    <p><?php echo $error; ?></p>
    <a href="login.html">Volver al formulario de inicio de sesión</a>
</body>
</html>
