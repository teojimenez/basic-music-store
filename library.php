<?php
    session_start();
    if (!isset($_SESSION["username"]))
        header("location:index.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>

<!-- Cada usuario registrado debe tener una colección personal de canciones.

Mostrar la colección de canciones del usuario en la página principal 
después del inicio de sesión. -->