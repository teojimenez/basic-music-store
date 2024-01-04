<?php
    include 'db_config.php';
    session_start();
    // session_destroy();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Please Login</h2>
    <div>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" class="field" name="login" value="Login">
        </form>
    </div>

    <?php
        if(isset($_POST['login']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $query = "SELECT * FROM users WHERE name = ?";
            $result = $conn->prepare($query); //preparar la consulta (prevenir inyecciones sql)
            $result->bind_param("s", $username); //vinculacion de parametros (s: tipo de dato "string")
            $result->execute(); //ejecuccion de la query
            $resultSet = $result->get_result(); //obtener las filas devueltas

            var_dump($resultSet->num_rows); // Verificar el contenido de $row
            if($resultSet->num_rows > 0) //si hay mas de una fila ejecutar
            {
                $row = $resultSet->fetch_assoc(); //split
                var_dump($row); // Verificar el contenido de $row
                echo "Password ingresada: " . $password . "<br>";
                echo "Hash almacenado: " . $row['password'] . "<br>";

                if(password_verify($password,  $row['password']))
                {
                    echo "FUNCIONA";
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $password;
                    $_SESSION['id'] = $row['id'];
                }
                else
                    echo "Contraseña Incorecta";
            }
            $resultSet->close();
        // }
        // header("Location:home.php");
        }
        if(isset($_SESSION["username"]))
        {
           header("Location:home.php");
        }
    
?>


</body>
</html>