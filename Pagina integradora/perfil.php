<?php
// Configuración de la base de datos, no se si lo tengan igual
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "integradora";

// Iniciar sesión para rastrear al usuario actual
session_start();

// Verificar si el usuario ha iniciado sesión
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

// Obtener información del usuario actual
$nombre = $_SESSION['nombre'];
$apellido = $_SESSION['apellido'];

$stmt = $conn->prepare("SELECT nombre, apellido, password FROM usuarios WHERE nombre = ? AND apellido = ?");
$stmt->bind_param("ss", $nombre, $apellido);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<script>
            alert('Usuario no encontrado');
            window.location.href = 'login.html';
          </script>";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="stylesperfil.css">
</head>
<body>
    <div class="profile-container">
        <h2>Perfil de Usuario</h2>
        <div class="profile-item">
            <label>Nombre:</label>
            <span><?php echo htmlspecialchars($user['nombre']); ?></span>
            <button onclick="editField('nombre', '<?php echo htmlspecialchars($user['nombre']); ?>')">Editar</button>
        </div>
        <div class="profile-item">
            <label>Apellido:</label>
            <span><?php echo htmlspecialchars($user['apellido']); ?></span>
            <button onclick="editField('apellido', '<?php echo htmlspecialchars($user['apellido']); ?>')">Editar</button>
        </div>
        <div class="profile-item">
            <label>Contraseña:</label>
            <span>********</span>
            <button onclick="editField('password', '')">Editar</button>
        </div>
    </div>

    <!-- Modal para edición -->
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Editar <span id="fieldName"></span></h3>
            <form id="editForm" action="editar_usuario.php" method="POST">
                <input type="hidden" name="field" id="field">
                <label for="newValue">Nuevo valor:</label>
                <input type="text" name="newValue" id="newValue" required>
                <button type="submit">Guardar</button>
                <button type="button" onclick="closeModal()">Cancelar</button>
            </form>
        </div>
    </div>

    <script>
        function editField(field, currentValue) {
            document.getElementById("editModal").style.display = "block";
            document.getElementById("fieldName").innerText = field;
            document.getElementById("field").value = field;
            document.getElementById("newValue").value = currentValue;
            if (field === 'password') {
                document.getElementById("newValue").type = "password";
            } else {
                document.getElementById("newValue").type = "text";
            }
        }

        function closeModal() {
            document.getElementById("editModal").style.display = "none";
        }
    </script>
</body>
</html>