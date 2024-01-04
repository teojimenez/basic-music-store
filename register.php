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
    <h2>Please Register</h2>
    <div>
        <form action="register.php" method="post">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" class="field" name="register" value="register">
        </form>
    </div>

    <?php
        if(isset($_POST['register']))
        {
            $username = $_POST['username'];
            $passwd = $_POST['password'];


            // $salt = bin2hex(random_bytes(16));

            $hashed_password = password_hash($passwd, PASSWORD_DEFAULT);

            $query = " INSERT INTO users (name, password) VALUES ('$username', '$hashed_password') ";
            // $query = mysqli_query($conn," SELECT * FROM users WHERE name = '$username' AND password = '$passwd' ");
            // $row =  mysqli_fetch_array($query);
            if($conn->query($query))
                echo "USUARIO REGISTRADO CORRECTAMENTE";
            else
                echo "Error".$query."<br>".$conn->error;

        }
        // if(isset($_SESSION["username"]))
        // {
        //    header("Location:home.php");
        // }
    ?>

</body>
</html>