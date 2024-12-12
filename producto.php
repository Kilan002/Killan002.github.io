<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "integradora";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
    } else {
        die("Producto no encontrado.");
    }
} else {
    die("ID inválido.");
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto - <?php echo isset($producto['titulo']) ? htmlspecialchars($producto['titulo']) : "Título no disponible"; ?></title>
    <link rel="stylesheet" href="producto.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="index.php">
                <img src="logo.png" alt="Logo" class="logo-img">
            </a>
        </div>
        <nav class="nav">
            <a href="index.php" class="nav-link">Inicio</a>
            <a href="usuarios.php" class="nav-link">Usuarios</a>
            <a href="login.html">
                <img src="login.png" alt="Login" class="login-icon">
            </a>
        </nav>
    </header>
    <div class="product-page">
        <div class="product-gallery">
            <div class="main-image">
                <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Producto principal" class="main-image-img" id="mainImage">
            </div>
        </div>
        <div class="product-details">
            <h1><?php echo isset($producto['titulo']) ? htmlspecialchars($producto['titulo']) : "Título no disponible"; ?></h1>
            <p class="price">$ <?php echo isset($producto['precio']) ? htmlspecialchars($producto['precio']) : "No disponible"; ?></p>
            <p class="description"><?php echo isset($producto['descripcion']) ? htmlspecialchars($producto['descripcion']) : "No disponible"; ?></p>
            <button class="buy-btn" onclick="openModal()">Comprar ahora</button>           
             <script>
        function changeImage(image) {
            document.getElementById('mainImage').src = image;
        }
    </script>
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Formulario de Pago</h2>
            <input type="hidden" id="productId" value="1"> <!-- ID del producto -->
            <input type="text" id="name" placeholder="Nombre del titular">
            <div id="error-name" class="error-message"></div>
            <input type="text" id="cardNumber" placeholder="Número de tarjeta">
            <div id="error-cardNumber" class="error-message"></div>
            <input type="text" id="expiry" placeholder="Fecha de vencimiento (MM/AA)">
            <div id="error-expiry" class="error-message"></div>
            <input type="text" id="cvv" placeholder="CVV">
            <div id="error-cvv" class="error-message"></div>
            <input type="text" id="postalCode" placeholder="Código Postal">
            <div id="error-postalCode" class="error-message"></div>
            <button onclick="validateAndSubmit()">Pagar</button>
        </div>
    </div>
    <script>
        // Abre el modal
        function openModal() {
            document.getElementById('paymentModal').style.display = 'block';
        }
    
        // Cierra el modal y limpia mensajes de error
        function closeModal() {
            document.getElementById('paymentModal').style.display = 'none';
            clearErrorMessages();
        }
    
        // Valida los campos y envía el pago
        function validateAndSubmit() {
            clearErrorMessages();
    
            let isValid = true;
    
            // Validar campos
            if (!document.getElementById('name').value.trim()) {
                showError('error-name', 'Por favor ingrese el nombre en la tarjeta.');
                isValid = false;
            }
    
            if (!document.getElementById('cardNumber').value.trim()) {
                showError('error-cardNumber', 'Por favor ingrese el número de tarjeta.');
                isValid = false;
            } else if (document.getElementById('cardNumber').value.length < 16) {
                showError('error-cardNumber', 'El número de tarjeta debe tener 16 dígitos.');
                isValid = false;
            }
    
            if (!document.getElementById('expiry').value.trim()) {
                showError('error-expiry', 'Por favor ingrese la fecha de vencimiento.');
                isValid = false;
            }
    
            if (!document.getElementById('cvv').value.trim()) {
                showError('error-cvv', 'Por favor ingrese el CVV.');
                isValid = false;
            } else if (document.getElementById('cvv').value.length < 3) {
                showError('error-cvv', 'El CVV debe tener 3 dígitos.');
                isValid = false;
            }
    
            if (!document.getElementById('postalCode').value.trim()) {
                showError('error-postalCode', 'Por favor ingrese el código postal.');
                isValid = false;
            }
    
            // Si todos los campos son válidos
            if (isValid) {
                alert('Pago exitoso');
                closeModal();
            }
        }
    
        // Muestra un mensaje de error
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.innerText = message;
            errorElement.style.display = 'block';
        }
    
        // Limpia todos los mensajes de error
        function clearErrorMessages() {
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach((msg) => {
                msg.style.display = 'none';
                msg.innerText = '';
            });
        }
    </script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
            border-radius: 8px;
        }
        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close-btn:hover {
            color: black;
        }
        input {
            width: calc(100% - 20px);
            margin: 10px 0;
            padding: 8px;
            font-size: 14px;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 12px;
            margin: 5px 0 10px;
            display: none;
        }
    </style>
</body>
</html>