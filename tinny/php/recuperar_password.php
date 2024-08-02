<?php
global $pdo;
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];

    // Generar una nueva contraseña aleatoria
    $nueva_contrasena = bin2hex(random_bytes(8)); // Contraseña de 16 caracteres
    $hashed_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

    // Actualizar la contraseña en la base de datos
    $stmt = $pdo->prepare("UPDATE usuarios SET contrasena = ? WHERE correo = ?");
    if ($stmt->execute([$hashed_password, $correo])) {
        // Enviar el correo con la nueva contraseña
        $asunto = "Recuperación de Contraseña";
        $mensaje = "Tu nueva contraseña es: " . $nueva_contrasena;
        if (mail($correo, $asunto, $mensaje)) {
            echo "Se ha enviado un correo con la nueva contraseña a tu dirección.";
        } else {
            echo "Error al enviar el correo. Por favor, intenta nuevamente.";
        }
    } else {
        echo "Error al procesar la solicitud.";
    }
}
?>
