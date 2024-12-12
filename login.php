<?php
session_start();

// Configuración de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "integradora";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Validar datos del formulario
if (empty($_POST['nombre']) || empty($_POST['password'])) {
    echo "Por favor, completa todos los campos.";
    exit();
}

$nombre = trim($_POST['nombre']);
$user_password = trim($_POST['password']);

// Preparar y ejecutar consulta
$sql = "SELECT id, nombre, role, password FROM usuarios WHERE nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nombre);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Verificar contraseña
    if (password_verify($user_password, $row['password'])) {
        // Asignar datos a la sesión
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['nombre'];
        $_SESSION['role'] = (bool)$row['role']; // Convertir rol explícitamente a booleano

        // Redirigir al dashboard
        header("Location: index.php");
        exit();
    } else {
        // Contraseña incorrecta
        echo "Contraseña incorrecta.";
    }
} else {
    // Usuario no encontrado
    echo "Usuario no encontrado.";
}

// Cerrar conexión y recursos
$stmt->close();
$conn->close();
?>