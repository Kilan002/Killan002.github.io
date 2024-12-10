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

$user_id = $_SESSION['user_id'];

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$password = $_POST['password'];

if (!empty($password)) {
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);
    $sql = "UPDATE usuarios SET nombre=?, apellido=?, password=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $apellido, $password_hashed, $user_id);
} else {
    $sql = "UPDATE usuarios SET nombre=?, apellido=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nombre, $apellido, $user_id);
}

if ($stmt->execute()) {
    echo "<script>alert('Cambios guardados exitosamente'); window.location.href='perfil.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>