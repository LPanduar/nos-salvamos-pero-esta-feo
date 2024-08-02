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
    <a href="./preferencias.php">Preferencias</a>
    <a href="./php/logout.php">Cerrar Sesión</a>
    <a href="./secciones/b_gestionar_encuestas.html">Gestión de encuestas</a>
    <a href="./secciones/c_mostrar_graficos.html">Graficar</a>
    <a href="./perfil.php">Perfil</a>
    <a href="./contacto.html">Contacto</a>
</div>

<div class="container">
    <h2>Bienvenido a la Red Tinny</h2>
    <!-- Formulario de Búsqueda -->
    <form method="get" action="./index.php">
        <input type="text" name="search" placeholder="Buscar encuestas...">
        <input type="submit" value="Buscar">
    </form>

    <!-- Contenido Principal -->

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
    if (!isset($_SESSION['id_usuario'])) {
        die("Error: Usuario no autenticado.");
    }

    $id_usuario = $_SESSION['id_usuario']; // Asegúrate de que `id_usuario` esté en la sesión

    // Obtener el término de búsqueda, si existe
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Consulta para obtener las encuestas publicadas, con o sin búsqueda
    if ($search) {
        $sql = "SELECT id, nombre, pregunta FROM encuestas WHERE publicado = 'si' AND (nombre LIKE '%$search%' OR pregunta LIKE '%$search%')";
    } else {
        $sql = "SELECT id, nombre, pregunta FROM encuestas WHERE publicado = 'si'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Mostrar cada encuesta
        while($row = $result->fetch_assoc()) {
            echo "<div class='encuesta'>";
            echo "<h3>" . $row["nombre"] . "</h3>";
            echo "<p>" . $row["pregunta"] . "</p>";

            // Consulta para obtener las opciones de la encuesta actual
            $sql_opciones = "SELECT id, opcion FROM opciones WHERE id_encuesta = " . $row["id"];
            $result_opciones = $conn->query($sql_opciones);

            if ($result_opciones->num_rows > 0) {
                echo "<form method='post' action='./php/votar.php'>";
                while($row_opcion = $result_opciones->fetch_assoc()) {
                    echo "<div class='opcion'>";
                    echo "<input type='radio' name='opcion' value='" . $row_opcion["id"] . "'>";
                    echo "<label>" . $row_opcion["opcion"] . "</label>";
                    echo "</div>";
                }
                echo "<input type='hidden' name='encuesta_id' value='" . $row["id"] . "'>";
                echo "<input type='submit' value='Votar'>";
                echo "</form>";
            } else {
                echo "No hay opciones disponibles para esta encuesta.";
            }

            echo "</div>";
        }
    } else {
        echo "No hay encuestas disponibles.";
    }

    // Cerrar conexión
    $conn->close();
    ?>
</div>
</body>
</html>
