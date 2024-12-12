<?php
//esta pagina es para agregar de forma dinamica los productos, las imagenes se van a 
//la carpeta uploads
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//empece a usar esto en varias partes del codigo, basicamente lo que hace
//es abrir sesion solo si no esta abierta
if (!isset($_SESSION['role']) || (int)$_SESSION['role'] !== 1) {
    echo "<script>alert('Acceso denegado.'); window.location.href='index.php';</script>";
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "integradora";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="agrega_prod.css">
</head>
<body>
<header class="header">
        <div class="logo">
            <a href="index.php">
                <img src="logo.png" alt="Logo" class="logo-img">
            </a>
        </div>
        <nav class="nav">
            <?php if (isset($_SESSION['role']) && (int)$_SESSION['role'] === 1): ?>
                <a href="agregar_producto.php" id="add-product-btn" class="nav-link">Agregar Producto</a>
            <?php endif; ?>
            <a href="index.php" class="nav-link">Inicio</a>
            <a href="usuarios.php" class="nav-link">Usuarios</a>
            <a href="login.html">
                <img src="login.png" alt="Login" class="login-icon">
            </a>
        </nav>
    </header>
    <main>
        <form action="agregar_producto.php" method="POST" enctype="multipart/form-data">
            <label for="imagen">Imagen del Producto:</label><br>
            <input type="file" name="imagen" id="imagen" required><br><br>
            
            <label for="titulo">Título:</label><br>
            <input type="text" name="titulo" id="titulo" required><br><br>
            
            <label for="descripcion">Descripción:</label><br>
            <textarea name="descripcion" id="descripcion" rows="3" cols="30" required></textarea><br><br>
            
            <label for="precio">Precio:</label><br>
            <input type="number" name="precio" id="precio" step="0.01" required><br><br>
            
            <button type="submit">Agregar Producto</button>
        </form>
    </main>
</body>
</html>

<?php
exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $directorio = "uploads/";
    $nombreArchivo = basename($_FILES["imagen"]["name"]);
    $rutaArchivo = $directorio . uniqid() . "_" . $nombreArchivo;

    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true); // Crea la carpeta si no existe
    }

    if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaArchivo)) {
        die("Error al mover la imagen al directorio.");
    }
} else {
    die("Error al subir la imagen.");
}

if (isset($_POST['titulo'], $_POST['descripcion'], $_POST['precio'])) {
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio = $conn->real_escape_string($_POST['precio']);
    $vendedor_id = $_SESSION['user_id'];

    $sql = "INSERT INTO productos (titulo, descripcion, precio, imagen, vendedor_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("ssdsd", $titulo, $descripcion, $precio, $rutaArchivo, $vendedor_id);

    if ($stmt->execute()) {
        echo "<script>alert('Producto agregado exitosamente.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error al agregar el producto. Inténtalo nuevamente más tarde.'); window.history.back();</script>";
    }

    $stmt->close();
} else {
    die("<script>alert('Faltan datos para procesar la solicitud.'); window.history.back();</script>");
}

$conn->close();
?>

