<?php
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
$confirm_password = $_POST['confirmacion'];
$role = ($_POST['role'] === 'vendedor') ? 1 : 0;

if ($password !== $confirm_password) {
    die("Las contraseñas no coinciden.");
}
//esto se encripta por obra y gracia del señor, cambienlo urgente
$password_hashed = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $nombre, $apellido, $password_hashed, $role);

if ($stmt->execute()) {
    echo "<script>
            alert('Registro exitoso');
            window.location.href = 'index.html';
          </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>