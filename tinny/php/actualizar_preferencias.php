<?php
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

// Obtener el ID del usuario desde la sesión (esto asume que ya has gestionado sesiones)
session_start();
$id_usuario = $_SESSION['id_usuario']; // Asegúrate de que `usuario_id` esté en la sesión

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$sexo = $_POST['sexo'];
$edad = $_POST['edad'];

// Manejar la foto de perfil
$foto_path = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
    $foto_tmp_name = $_FILES['foto']['tmp_name'];
    $foto_name = basename($_FILES['foto']['name']);
    $foto_dir = 'uploads/';
    $foto_path = $foto_dir . $foto_name;
    move_uploaded_file($foto_tmp_name, $foto_path);
}

// Actualizar preferencias en la base de datos
$sql = "UPDATE usuarios SET nombre = ?, sexo = ?, edad = ?, foto = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssisi', $nombre, $sexo, $edad, $foto_path, $id_usuario);

if ($stmt->execute()) {
    echo "Preferencias actualizadas correctamente.";
} else {
    echo "Error al actualizar preferencias: " . $stmt->error;
}

// Cerrar conexión
$stmt->close();
$conn->close();

// Redirigir de vuelta a la página de preferencias
header("Location: ../preferencias.php");
exit();
?>
