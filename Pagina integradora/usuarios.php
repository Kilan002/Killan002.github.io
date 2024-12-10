<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Debes iniciar sesión primero'); window.location.href='login.html';</script>";
    exit();
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "integradora");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos actuales del usuario desde la base de datos
$user_id = $_SESSION['user_id'];
$sql = "SELECT nombre, apellido FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="index.html">
                <img src="logo.png" alt="Logo" class="logo-img">
            </a>
        </div>
        <nav class="nav">
            <a href="index.html" class="nav-link">Inicio</a>
            <a href="usuarios.php" class="nav-link">Usuarios</a>
            <a href="login.html">
                <img src="login.png" alt="Login" class="login-icon">
            </a>
        </nav>
    </header>

    <div class="user-profile">
        <div >
            <h2>Editar Información del Usuario</h2>
        </div>
        <form action="actualizar_usuario.php" method="POST" class="user-form">
            <div class="input-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
            </div>
            <div class="input-group">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($user['apellido']); ?>" required>
            </div>
            <div class="input-group">
                <label for="password">Nueva Contraseña:</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit" class="btn-guardar">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>