<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Filtrar Encuestas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="navbar">
    <a href="../index.php">Inicio</a>
    <a href="../buscar_encuestas.php">Mis Encuestas</a>
    <a href="../preferencias.php">Graficar</a>
    <a href="../php/logout.php">Yo</a>
</div>

<div class="container">
    <h2>Filtrar Encuestas</h2>
    <form method="GET" action="">
        <label for="nombre_encuesta">Nombre de la Encuesta (Obligatorio):</label>
        <select id="nombre_encuesta" name="nombre_encuesta" required>
            <option value="">Seleccione una encuesta</option>
            <?php
            require '../Configuraciones/conexion.php';

            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            $result = $conn->query("SELECT id, nombre FROM encuestas WHERE publicado = 'si'");

            if (!$result) {
                die("Error en la consulta: " . $conn->error);
            }

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nombre']) . "</option>";
                }
            } else {
                echo "<option value=''>No hay encuestas publicadas</option>";
            }
            ?>
        </select>
        <br>
        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" id="nombre_usuario" name="nombre_usuario">
        <br>
        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad">
        <br>
        <label for="sexo">Sexo:</label>
        <select id="sexo" name="sexo">
            <option value="">Seleccione el sexo</option>
            <option value="hombre">hombre</option>
            <option value="mujer">mujer</option>
        </select>
        <br>
        <label for="periodo_megusta">Periodo para Me Gusta:</label>
        <input type="date" id="periodo_megusta_desde" name="periodo_megusta_desde">
        <input type="date" id="periodo_megusta_hasta" name="periodo_megusta_hasta">
        <br>
        <label for="periodo_reacciones">Periodo para Reacciones:</label>
        <input type="date" id="periodo_reacciones_desde" name="periodo_reacciones_desde">
        <input type="date" id="periodo_reacciones_hasta" name="periodo_reacciones_hasta">
        <br>
        <button type="submit">Filtrar</button>
    </form>
</div>

<div class="resultados">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['nombre_encuesta']) && !empty($_GET['nombre_encuesta'])) {
    $nombre_encuesta = $_GET['nombre_encuesta'] ?? '';
    $nombre_usuario = $_GET['nombre_usuario'] ?? '';
    $edad = $_GET['edad'] ?? '';
    $sexo = $_GET['sexo'] ?? '';
    $periodo_megusta_desde = $_GET['periodo_megusta_desde'] ?? '';
    $periodo_megusta_hasta = $_GET['periodo_megusta_hasta'] ?? '';
    $periodo_reacciones_desde = $_GET['periodo_reacciones_desde'] ?? '';
    $periodo_reacciones_hasta = $_GET['periodo_reacciones_hasta'] ?? '';

    // Consulta base
    $query = "SELECT encuestas.nombre AS encuesta_nombre, respuestas.respuesta, respuestas.voto, usuarios.nombre AS usuario_nombre, usuarios.edad, usuarios.sexo, respuestas.fecha 
                  FROM respuestas
                  JOIN encuestas ON respuestas.id_encuesta = encuestas.id
                  JOIN usuarios ON respuestas.id_usuario = usuarios.id
                  WHERE respuestas.voto = 'si' AND encuestas.id = " . intval($nombre_encuesta);

    // Filtros
    if ($nombre_usuario) {
        $query .= " AND usuarios.nombre LIKE '%" . $conn->real_escape_string($nombre_usuario) . "%'";
    }
    if ($edad) {
        $query .= " AND usuarios.edad = " . intval($edad);
    }
    if ($sexo) {
        $query .= " AND usuarios.sexo = '" . $conn->real_escape_string($sexo) . "'";
    }
    if ($periodo_megusta_desde && $periodo_megusta_hasta) {
        $query .= " AND respuestas.fecha BETWEEN '" . $conn->real_escape_string($periodo_megusta_desde) . "' AND '" . $conn->real_escape_string($periodo_megusta_hasta) . "'";
    }
    if ($periodo_reacciones_desde && $periodo_reacciones_hasta) {
        $query .= " AND respuestas.fecha BETWEEN '" . $conn->real_escape_string($periodo_reacciones_desde) . "' AND '" . $conn->real_escape_string($periodo_reacciones_hasta) . "'";
    }

    // Obtener datos totales de la encuesta seleccionada
    $total_query = "SELECT COUNT(*) AS total_respuestas FROM respuestas WHERE id_encuesta = " . intval($nombre_encuesta) . " AND voto = 'si'";
    $total_result = $conn->query($total_query);
    $total_respuestas = ($total_result && $total_result->num_rows > 0) ? $total_result->fetch_assoc()['total_respuestas'] : 0;

    // Ejecutar consulta de datos filtrados
    $result = $conn->query($query);
    $filtered_responses = $result->num_rows;

    if ($result) {
        if ($result->num_rows > 0) {
            echo "<table border='1'>
                        <tr>
                            <th>Encuesta</th>
                            <th>Respuesta</th>
                            <th>Voto</th>
                            <th>Usuario</th>
                            <th>Edad</th>
                            <th>Sexo</th>
                            <th>Fecha</th>
                        </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                            <td>" . htmlspecialchars($row['encuesta_nombre']) . "</td>
                            <td>" . htmlspecialchars($row['respuesta']) . "</td>
                            <td>" . htmlspecialchars($row['voto']) . "</td>
                            <td>" . htmlspecialchars($row['usuario_nombre']) . "</td>
                            <td>" . htmlspecialchars($row['edad']) . "</td>
                            <td>" . htmlspecialchars($row['sexo']) . "</td>
                            <td>" . htmlspecialchars($row['fecha']) . "</td>
                          </tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron resultados.";
        }
    } else {
        echo "Error en la consulta: " . $conn->error;
    }
    ?>
</div>

<div class="grafico">
    <canvas id="myChart"></canvas>
</div>

<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const totalRespuestas = <?php echo $total_respuestas; ?>;
    const filteredResponses = <?php echo $filtered_responses; ?>;
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Respuestas', 'Respuestas Filtradas'],
            datasets: [{
                label: 'Número de Respuestas',
                data: [totalRespuestas, filteredResponses],
                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php
}
?>
</body>
</html>