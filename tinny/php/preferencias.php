<?php
global $pdo;
require 'database.php';

// Obtener el ID del usuario desde la sesión (esto asume que ya has gestionado sesiones)
session_start();
$id_usuario = $_SESSION['id_usuario']; // Asegúrate de que `usuario_id` esté en la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $nombre = $_POST['nombre'];
    $sexo = $_POST['sexo'];
    $edad = $_POST['edad'];
    $foto = $_FILES['foto']['name'];

    if ($foto) {
        $target_dir = "../img/";
        $target_file = $target_dir . basename($foto);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
    }

    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, sexo = ?, edad = ?, foto = ? WHERE id = ?");
    if ($stmt->execute([$nombre, $sexo, $edad, $foto, $id_usuario])) {
        header("Location: ../index.php");
    } else {
        echo "Error al actualizar preferencias.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Preferencias</title>
</head>
<body>
<div class="navbar">
    <a href="../index.php">Inicio</a>
    <a href="../buscar_encuestas.php">Buscar Encuestas</a>
    <a href="../preferencias.php">Preferencias</a>
    <a href="../php/logout.php">Cerrar Sesión</a>
</div>

<div class="container">
    <h2>Preferencias de Usuario</h2>
    <form action="./preferencias.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre">

        <label for="sexo">Sexo:</label>
        <select id="sexo" name="sexo">
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
            <option value="O">Otro</option>
        </select>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad">

        <label for="foto">Foto de Perfil:</label>
        <input type="file" id="foto" name="foto">

        <button type="submit">Actualizar Preferencias</button>
    </form>
</div>
</body>
</html>
