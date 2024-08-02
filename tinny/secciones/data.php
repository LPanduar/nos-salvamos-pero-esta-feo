<?php
// Datos de conexión a la base de datos
$host = 'localhost';
$db = 'tinny';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("No se pudo conectar a la base de datos: " . $e->getMessage());
}
// Obtener el ID del usuario desde la sesión (esto asume que ya has gestionado sesiones)
session_start();
$id_usuario = $_SESSION['id_usuario']; // Asegúrate de que `usuario_id` esté en la sesión

$query = $pdo->prepare("
    SELECT encuestas.nombre AS encuesta, 
           SUM(respuestas.voto = 'si') AS me_gusta
    FROM respuestas 
    JOIN encuestas ON respuestas.id_encuesta = encuestas.id
    WHERE encuestas.id_usuario = ? 
    GROUP BY encuestas.id
");
$query->execute([$id_usuario]);

$resultados = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultados);


?>