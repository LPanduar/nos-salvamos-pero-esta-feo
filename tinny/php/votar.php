<?php
session_start();

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

// Obtener datos del formulario
$id_encuesta = $_POST['encuesta_id'];
$id_opcion = $_POST['opcion'];
$id_usuario = $_SESSION['id_usuario']; // Asegúrate de que `id_usuario` esté en la sesión

// Consulta para obtener la opción seleccionada
$sql_opcion = "SELECT opcion FROM opciones WHERE id = ?";
$stmt_opcion = $conn->prepare($sql_opcion);
$stmt_opcion->bind_param("i", $id_opcion);
$stmt_opcion->execute();
$stmt_opcion->bind_result($opcion_text);
$stmt_opcion->fetch();
$stmt_opcion->close();

// Insertar el voto en la tabla respuestas
$sql_insert = "INSERT INTO respuestas (id_encuesta, id_usuario, respuesta, voto) VALUES (?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$voto = "voto"; // Puedes ajustar este valor según lo que desees almacenar en la columna 'voto'
$stmt_insert->bind_param("iiss", $id_encuesta, $id_usuario, $opcion_text, $voto);
$stmt_insert->execute();

// Cerrar conexión
$stmt_insert->close();
$conn->close();

// Redirigir de vuelta a la página principal (o a donde desees)
header("Location: ../index.php");
exit();
?>
