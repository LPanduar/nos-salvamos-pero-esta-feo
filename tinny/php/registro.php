<?php
global $pdo;
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $sexo = $_POST['sexo'];
    $edad = $_POST['edad'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena, sexo, edad) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$nombre, $correo, $contrasena, $sexo, $edad])) {
        header("Location: ../login.php");
    } else {
        echo "Error al registrarse.";
    }
}
?>
