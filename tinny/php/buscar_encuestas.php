global$pdo; <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>Buscar Encuestas</title>
</head>
<body>
<div class="navbar">
    <a href="../index.php">Inicio</a>
    <a href="../buscar_encuestas.php">Buscar Encuestas</a>
    <a href="../preferencias.php">Preferencias</a>
    <a href="../php/logout.php">Cerrar Sesión</a>
</div>

<div class="container">
    <h2>Buscar Encuestas</h2>
    <form action="../php/buscar_encuestas.php" method="GET">
        <label for="query">Buscar:</label>
        <input type="text" id="query" name="query" placeholder="Escribe tu búsqueda">
        <button type="submit">Buscar</button>
    </form>

    <div class="resultados">
        <?php
        global $pdo;
        require 'database.php';

        // Obtener el ID del usuario desde la sesión (esto asume que ya has gestionado sesiones)
        session_start();
        $id_usuario = $_SESSION['id_usuario']; // Asegúrate de que `usuario_id` esté en la sesión

        if (isset($_GET['query'])) {
            $query = $_GET['query'];
            $stmt = $pdo->prepare("SELECT * FROM encuestas WHERE nombre LIKE ? OR pregunta LIKE ?");
            $stmt->execute(["%$query%", "%$query%"]);
            $resultados = $stmt->fetchAll();

            foreach ($resultados as $encuesta) {
                echo "<div class='encuesta'>";
                echo "<h3>" . htmlspecialchars($encuesta['nombre']) . "</h3>";
                echo "<p>" . htmlspecialchars($encuesta['pregunta']) . "</p>";
                echo "<a href='encuesta.php?id=" . $encuesta['id'] . "'>Votar</a>";
                echo "</div>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
