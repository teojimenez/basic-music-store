<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $db = "music_store";

    $conn = new mysqli($host, $username, $password, $db);
    if ($conn->connect_error)
        die('Error de conexion: '.$conn->connect_error);
?>