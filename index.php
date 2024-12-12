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

// Obtener los productos dinámicos de la base de datos
$sql = "SELECT id, titulo, descripcion, imagen FROM productos";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="styles.css">
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

<div class="carousel">
    <div class="carousel-track-container">
        <div class="carousel-track">
            <!-- Productos estáticos -->
            <div class="product-card">
                <img src="product1.jpg" alt="Volkswagen Golf" class="product-image">
                <h3 class="product-title">Volkswagen Golf</h3>
                <p class="product-description">60,000 MXN</p>
                <a href="producto1.html" class="product-link">Ver más</a>
            </div>
            <div class="product-card">
                <img src="product2.png" alt="Ford Focus" class="product-image">
                <h3 class="product-title">Ford Focus</h3>
                <p class="product-description">120,000 MXN</p>
                <a href="producto2.html" class="product-link">Ver más</a>
            </div>
            <div class="product-card">
                <img src="product3.webp" alt="Toyota" class="product-image">
                <h3 class="product-title">Toyota</h3>
                <p class="product-description">95,000 MXN</p>
                <a href="producto3.html" class="product-link">Ver más</a>
            </div>
            <div class="product-card">
                <img src="product4.webp" alt="Honda" class="product-image">
                <h3 class="product-title">Honda</h3>
                <p class="product-description">90,000 MXN</p>
                <a href="producto4.html" class="product-link">Ver más</a>
            </div>

            <!-- Productos dinámicos desde la base de datos -->
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $titulo = $row['titulo'];
                    $descripcion = $row['descripcion'];
                    $imagen = $row['imagen'];

                    echo '<div class="product-card">';
                    echo '<img src="' . htmlspecialchars($imagen) . '" alt="' . htmlspecialchars($titulo) . '" class="product-image">';
                    echo '<h3 class="product-title">' . htmlspecialchars($titulo) . '</h3>';
                    echo '<p class="product-description">' . htmlspecialchars($descripcion) . '</p>';
                    echo '<a href="producto.php?id=' . htmlspecialchars($id) . '" class="product-link">Ver más</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay productos nuevos disponibles.</p>';
            }
            ?>
        </div>
    </div>
    <button class="carousel-button left">◀</button>
    <button class="carousel-button right">▶</button>
</div>

<script src="javascript.js"></script>
<p class="centrado">Lorenzo Antonio Heredia Bernal
    <br>
Luis Enrique Pérez Rosales
<br>
juan carlos santos carrillo
</p>
<style>
    p.centrado{
        text-align:center;
    }
</style>
</body>
</html>
<?php
$conn->close();
?>
