<?php
global $pdo;
require 'database.php';

// Obtener el ID del usuario desde la sesión (esto asume que ya has gestionado sesiones)
session_start();
$id_usuario = $_SESSION['id_usuario']; // Asegúrate de que `id_usuario` esté en la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $sexo = $_POST['sexo'];
    $edad = $_POST['edad'];
    $foto = $_FILES['foto']['name'];
    $categoria = $_POST['categoria'] ?? null;

    if ($foto) {
        $target_dir = "../imagenes/"; // Corrige la ruta si es necesario
        $target_file = $target_dir . basename($foto);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
    } else {
        // Mantener la foto actual si no se carga una nueva
        $stmt = $pdo->prepare("SELECT foto FROM usuarios WHERE id = ?");
        $stmt->execute([$id_usuario]);
        $current_foto = $stmt->fetchColumn();
        $foto = $current_foto;
    }

    // Actualizar las preferencias del usuario
    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, sexo = ?, edad = ?, foto = ?, categoria_id = ? WHERE id = ?");
        $stmt->execute([$nombre, $sexo, $edad, $foto, $categoria, $id_usuario]);
        echo "Preferencias actualizadas exitosamente.";
    } catch (PDOException $e) {
        echo "Error al actualizar las preferencias: " . $e->getMessage();
    }
}

// Obtener la información actual del usuario para mostrar en el formulario
$stmt = $pdo->prepare("SELECT nombre, sexo, edad, foto, categoria_id FROM usuarios WHERE id = ?");
$stmt->execute([$id_usuario]);
$user = $stmt->fetch();
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
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>">

        <label for="sexo">Sexo:</label>
        <select id="sexo" name="sexo">
            <option value="M" <?php echo $user['sexo'] == 'M' ? 'selected' : ''; ?>>Masculino</option>
            <option value="F" <?php echo $user['sexo'] == 'F' ? 'selected' : ''; ?>>Femenino</option>
            <option value="O" <?php echo $user['sexo'] == 'O' ? 'selected' : ''; ?>>Otro</option>
        </select>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" value="<?php echo htmlspecialchars($user['edad']); ?>">

        <label for="foto">Foto de Perfil:</label>
        <?php if ($user['foto']): ?>
            <div>
                <img src="../imagenes/<?php echo htmlspecialchars($user['foto']); ?>" alt="Foto de perfil" style="max-width: 150px; max-height: 150px;">
            </div>
        <?php endif; ?>
        <input type="file" id="foto" name="foto">

        <label for="categoria">Categoría:</label>
        <input type="text" id="categoria" name="categoria" value="<?php echo htmlspecialchars($user['categoria_id']); ?>">

        <button type="submit">Actualizar Preferencias</button>
    </form>
</div>
</body>
</html>
