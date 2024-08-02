<?php
include('conexion.php');

// Obtener el ID del usuario desde la sesión (esto asume que ya has gestionado sesiones)
session_start();
$id_usuario = $_SESSION['id_usuario']; // Asegúrate de que `id_usuario` esté en la sesión

if (isset($_POST['Guardar'])) {
    // Datos de la encuesta
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre_encuesta']);
    $pregunta = mysqli_real_escape_string($conn, $_POST['pregunta']);
    $tema = mysqli_real_escape_string($conn, $_POST['tema']);
    $publicado = 0; // Asigna el valor que desees para publicado
    $restriccionEdad = 0; // Asigna el valor que desees para restriccionEdad

    if (isset($_FILES['imagen']) && $_FILES['imagen']['name'] != "") {
        $tipo = $_FILES['imagen']['type'];
        $temp = $_FILES['imagen']['tmp_name'];
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nuevoNombreImagen = uniqid() . '.' . $extension; // Generar un nombre único

        if (!((strpos($tipo, 'jpeg') || strpos($tipo, 'jpg') || strpos($tipo, 'png')))) {
            $_SESSION['mensaje'] = 'solo se permite archivos jpeg, jpg, png';
            header('location:../secciones/b_crear_encuestas.html');
        } else {
            // Insertar datos en la tabla encuestas
            $query = "INSERT INTO encuestas (nombre, pregunta, imagen, id_usuario, categoria, publicado, restriccionEdad) VALUES ('$nombre', '$pregunta', '$nuevoNombreImagen', $id_usuario, '$tema', $publicado, $restriccionEdad)";
            $resultado = mysqli_query($conn, $query);

            if ($resultado) {
                // Obtener el id de la última encuesta insertada
                $id_encuesta = mysqli_insert_id($conn);

                // Insertar opciones en la tabla opciones
                foreach ($_POST['respuestas'] as $respuesta) {
                    $respuesta = mysqli_real_escape_string($conn, $respuesta);
                    $query_opcion = "INSERT INTO opciones (opcion, id_encuesta) VALUES ('$respuesta', '$id_encuesta')";
                    mysqli_query($conn, $query_opcion);
                }

                // Verificar si existe la carpeta imagenes
                if (!is_dir('../imagenes')) {
                    mkdir('../imagenes', 0777, true);
                }

                // Ruta de destino
                $destino = '../imagenes/' . $nuevoNombreImagen;

                // Mover el archivo
                if (move_uploaded_file($temp, $destino)) {
                    $_SESSION['mensaje'] = 'se ha subido correctamente';
                    header('location:../secciones/b_crear_encuestas.html');
                } else {
                    $_SESSION['mensaje'] = 'error al mover la imagen al directorio';
                    header('location:../secciones/b_crear_encuestas.html');
                }
            } else {
                $_SESSION['mensaje'] = 'ocurrio un error en el servidor';
                header('location:../secciones/b_crear_encuestas.html');
            }
        }
    }
}
?>
