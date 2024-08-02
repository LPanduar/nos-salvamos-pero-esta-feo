<?php
global $pdo;
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que las variables están definidas
    if (isset($_POST['correo']) && isset($_POST['contrasena'])) {
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];

        // Preparar la consulta SQL
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch();

        // Verificar la contraseña
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            session_start();
            session_regenerate_id(true); // Regenerar el ID de sesión
            $_SESSION['id_usuario'] = $usuario['id'];
            header("Location: ../index.php");
            exit;
        } else {
            echo "Correo o contraseña incorrectos.";
        }
    } else {
        echo "Correo o contraseña no proporcionados.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
