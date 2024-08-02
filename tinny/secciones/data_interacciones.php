<?php
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

$query = "
    SELECT 
        e.nombre AS encuesta,
        COUNT(r.id) AS interacciones
    FROM respuestas r
    INNER JOIN encuestas e ON r.id_encuesta = e.id
    WHERE e.id_usuario = :id_usuario
    GROUP BY e.id
    ORDER BY interacciones DESC
";

$stmt = $pdo->prepare($query);
$stmt->execute(['id_usuario' => $id_usuario]);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultados);
?>