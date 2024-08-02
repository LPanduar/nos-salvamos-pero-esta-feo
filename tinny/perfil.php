<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Red Social de Encuestas</title>
</head>
<body>
<div class="navbar">
    <a href="./index.php">Inicio</a>
    <a href="./buscar_encuestas.php">Buscar Encuestas</a>
    <a href="./preferencias.php">Preferencias</a>
    <a href="./php/logout.php">Cerrar Sesión</a>
    <a href="./perfil.php">Perfil</a>
</div>

<div class="container">
    <h2>Perfil del Usuario</h2>

    <?php
    // Configuración de la conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "1234";
    $dbname = "tinny";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener el ID del usuario desde la sesión (esto asume que ya has gestionado sesiones)
    session_start();
    $id_usuario = $_SESSION['id_usuario']; // Asegúrate de que `usuario_id` esté en la sesión

    // Consulta para obtener los datos del usuario
    $sql = "SELECT nombre, sexo, edad, foto FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($nombre, $sexo, $edad, $foto);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        echo "<h3>Nombre: $nombre</h3>";
        echo "<p><strong>Sexo:</strong> " . ($sexo === 'M' ? 'Masculino' : ($sexo === 'F' ? 'Femenino' : 'Otro')) . "</p>";
        echo "<p><strong>Edad:</strong> $edad años</p>";

        if ($foto) {
            echo "<p><strong>Foto de Perfil:</strong><br><img src='$foto' alt='Foto de Perfil' style='max-width: 200px; height: auto;'></p>";
        } else {
            echo "<p><strong>Foto de Perfil:</strong> No disponible</p>";
        }
    } else {
        echo "<p>No se encontraron datos del usuario.</p>";
    }

    // Cerrar conexión
    $stmt->close();
    $conn->close();
    ?>

    <a href="./preferencias.php">Editar Preferencias</a>
</div>
</body>
</html>
