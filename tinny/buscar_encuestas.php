<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/principal.css">
    <title>Buscar encuestas</title>
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
    <h2>Buscar Encuestas</h2>
    <form method="get" action="buscar_encuestas.php">
        <input type="text" name="query" placeholder="Buscar por nombre o categoría">
        <input type="submit" value="Buscar">
    </form>

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

    if (isset($_GET['query'])) {
        $query = $conn->real_escape_string($_GET['query']);

        // Consulta para buscar encuestas por nombre o categoría
        $sql = "SELECT id, nombre, pregunta FROM encuestas WHERE nombre LIKE '%$query%' OR categoria LIKE '%$query%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='encuesta'>";
                echo "<h3>" . $row["nombre"] . "</h3>";
                echo "<p>" . $row["pregunta"] . "</p>";
                echo "</div>";
            }
        } else {
            echo "No se encontraron encuestas.";
        }
    }

    // Cerrar conexión
    $conn->close();
    ?>
</div>
</body>
</html>
