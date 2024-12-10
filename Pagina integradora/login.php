<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "integradora";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$password = $_POST['password'];

$sql = "SELECT id, password FROM usuarios WHERE nombre = ? AND apellido = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $nombre, $apellido);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró al usuario
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verificar la contraseña
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido'] = $apellido;

        header("Location: index.html"); // Redirige al index
        exit();
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.location.href='login.html';</script>";
        exit();
    }
} else {
    echo "<script>alert('Usuario no encontrado'); window.location.href='login.html';</script>";
    exit();
}

$stmt->close();
$conn->close();
?>