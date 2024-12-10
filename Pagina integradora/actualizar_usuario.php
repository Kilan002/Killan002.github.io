<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Debes iniciar sesión primero'); window.location.href='login.html';</script>";
    exit();
}

$conn = new mysqli("localhost", "root", "", "integradora");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$password = $_POST['password'];

$sql = "UPDATE usuarios SET nombre = ?, apellido = ?";

$params = [$nombre, $apellido];
$types = "ss";
if (!empty($password)) {
    $sql .= ", password = ?";
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);
    $params[] = $password_hashed;
    $types .= "s";
}

$sql .= " WHERE id = ?";
$params[] = $user_id;
$types .= "i";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo "<script>alert('Datos actualizados correctamente'); window.location.href='usuarios.php';</script>";
} else {
    echo "Error al actualizar los datos: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
