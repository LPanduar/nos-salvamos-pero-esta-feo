<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tinny</title>
    <link rel="stylesheet" href="../Styles/Plantilla_inter.css">
    <link rel="stylesheet" href="../Styles/accionar_encuesta.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .encuesta-cuadro {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            width: 300px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
            display: inline-block;
            vertical-align: top;
        }
        .encuesta-cuadro img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body class="grid-container">
<nav class="navar">
    <div class="nav-links">
        <div class="nav-item">
            <img class="logo" src="../src/Tinny_logo.png" alt="Logo">
        </div>
        <div class="nav-item">
            <a href="../index.php">
                <img class="imagen" src="../src/home.png" alt="Inicio"> Inicio
            </a>
        </div>
        <div class="nav-item">
            <a href="../secciones/b_gestionar_encuestas.html">
                <img class="imagen" src="../src/note.png" alt="Mis encuestas"> Mis encuestas
            </a>
        </div>
        <div class="nav-item">
            <a href="../secciones/c_mostrar_graficos.html">
                <img class="imagen" src="../src/grafic.png" alt="Graficar"> Graficar
            </a>
        </div>
    </div>
</nav>

<article class="main">
    <h2>Buscar Encuestas</h2>
    <div class="cajabuscar">
        <form method="GET" action="b_eliminar_encuesta.php" id="buscarform">
            <fieldset>
                <input type="text" id="termino" name="termino" placeholder="Buscar" />
                <button class="button-search" type="submit">
                    <i class="search"></i>
                </button>
            </fieldset>
        </form>
    </div>

    <h2>Mis Encuestas</h2>
    <div id="encuestasContainer">
        <?php
        include('../Configuraciones/conexion.php');

        // Obtener el ID del usuario desde la sesión (esto asume que ya has gestionado sesiones)
        session_start();
        $id_usuario = $_SESSION['id_usuario']; // Asegúrate de que `usuario_id` esté en la sesión

        if (isset($_GET['termino'])) {
            $termino = mysqli_real_escape_string($conn, $_GET['termino']);
            // Consulta a la base de datos buscando las encuestas del usuario
            $query = "SELECT * FROM encuestas WHERE (nombre LIKE '%$termino%' OR categoria LIKE '%$termino%') AND id_usuario = $id_usuario";
        } else {
            // Consultar todas las encuestas que coincidan con las del usuario
            $query = "SELECT * FROM encuestas WHERE id_usuario = $id_usuario";
        }
        
        $resultado = mysqli_query($conn, $query);

        if (mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                echo "<div class='encuesta-cuadro'>";
                echo "<h3>" . $row['nombre'] . "</h3>";
                echo "<p>" . $row['pregunta'] . "</p>";
                echo "<p><strong>Tema:</strong> " . $row['categoria'] . "</p>";
                echo "<form method='POST' action='../Configuraciones/buscar_encuestas_eliminar.php'>";
                echo "<input type='hidden' name='encuesta_id' value='" . $row['id'] . "'>";
                echo "<button type='submit'>Eliminar</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No se encontraron encuestas.</p>";
        }

        mysqli_close($conn);
        ?>
    </div>
</article>
</body>
</html>