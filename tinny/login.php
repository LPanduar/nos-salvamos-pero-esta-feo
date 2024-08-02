<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login.css">
    <title>Inicio de Sesión</title>
</head>
<body>
<div class="container">
    <div class="left-panel">
        <img src="./src/Tinny_logo.png" alt="Logo"> <!-- Asegúrate de que la ruta sea correcta -->
        <h1>Iniciar Sesión</h1>
        <p>Introduce tus datos para iniciar sesión.</p>
    </div>
    <div class="right-panel">
        <h2>Inicio de Sesión</h2>
        <form method="post" action="./php/login.php">
            <input type="email" id="correo" name="correo" placeholder="Correo Electrónico" required>
            <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
            <input type="submit" value="Iniciar Sesión">
        </form>
        <a href="./recuperar_password.php">¿Olvidaste tu contraseña?</a>
        <a href="./registro.php">¿Quieres unirte registrate?</a>
    </div>
</div>
</body>
</html>
