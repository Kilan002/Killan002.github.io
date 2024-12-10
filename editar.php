<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "integradora";

session_start();

if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) {
    echo "<script>
            alert('Por favor inicia sesión primero');
            window.location.href = 'login.html';
          </script>";
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$field = $_POST['field'];
$newValue = $_POST['newValue'];
$nombre = $_SESSION['nombre'];
$apellido = $_SESSION['apellido'];

if ($field === 'password') {
    $newValue = password_hash($newValue, PASSWORD_BCRYPT);
}

$sql = "UPDATE usuarios SET $field = ? WHERE nombre = ? AND apellido = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $newValue, $nombre, $apellido);

if ($stmt->execute()) {
    echo "<script>
            alert('Campo actualizado exitosamente');
            window.location.href = 'perfil.php';
          </script>";
} else {
    echo "<script>
            alert('Error al actualizar el campo');
            window.location.href = 'perfil.php';
          </script>";
}

$stmt->close();
$conn->close();
?>
