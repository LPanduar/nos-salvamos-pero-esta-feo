<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Recuperar Contraseña</title>
</head>
<body>
<div class="container">
    <h2>Recuperar Contraseña</h2>
    <form action="./php/recuperar_password.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="correo" name="correo" required>

        <button type="submit">Recuperar</button>
    </form>
    <a href="./login.php">Iniciar Sesión</a>
</div>
</body>
</html>
