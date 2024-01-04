<?php
    include 'db_config.php';
    session_start();
    if (isset($_SESSION["username"]))
        header("location:home.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/index/index.css">
    <style>
      .error-content{
        color: red;
        position: absolute;
        top: 10%;
      }
    </style>
    <title>Document</title>
</head>
<body>
    <h4 class="error-content"></h4>
    <!-- <a href="login.php">Log In</a>
    <a href="register.php">Register</a> -->
    <div class="wrapper">
      <!-- <div class="title-text">
        <div class="title login">Login Form</div>
        <div class="title signup">Signup Form</div>
      </div> -->
      <div class="form-container">
        <div class="slide-controls">
          <input type="radio" name="slide" id="login" checked>
          <input type="radio" name="slide" id="signup">
          <label for="login" class="slide login">Login</label>
          <label for="signup" class="slide signup">Signup</label>
          <div class="slider-tab"></div>
        </div>
        <div class="form-inner">

          <form action="index.php" class="login" method="post">
            <!-- <pre>
            </pre> -->
            <div class="field">
              <input type="text" placeholder="Name" name="username" required>
            </div>
            <div class="field">
              <input type="password" placeholder="Password" name="password" required>
            </div>
            <!-- <div class="pass-link"><a href="#">Forgot password?</a></div> -->
            <div class="field btn">
              <div class="btn-layer"></div>
              <input type="submit" name="login" value="Login">
            </div>
            <div class="signup-link">Create an account? <a href="">Signup now</a></div>
          </form>
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

            //var_dump($resultSet->num_rows); // Verificar el contenido de $row
            if($resultSet->num_rows > 0) //si hay mas de una fila ejecutar
            {
                $row = $resultSet->fetch_assoc(); //split
                // var_dump($row); // Verificar el contenido de $row
                // echo "Password ingresada: " . $password . "<br>";
                // echo "Hash almacenado: " . $row['password'] . "<br>";

                if(password_verify($password,  $row['password']))
                {
                    // echo "FUNCIONA";
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $password;
                    $_SESSION['id'] = $row['id'];
                }
                else
                {
                  echo "<script>";
                  echo 'document.querySelector(".error-content").textContent = "USER OR PASSWORD WRONG"';
                  echo "</script>";
                }
            }
            $resultSet->close();
        // }
        // header("Location:home.php");
        }
        if (isset($_SESSION["username"]))
        {
          header("Location:home.php");
        }


        /////////
        if  (isset($_POST['register']))
        {
            $username = $_POST['username'];
            $passwd = $_POST['password'];

            $query = "SELECT * FROM users WHERE name = ?";
            $result = $conn->prepare($query);
            $result->bind_param("s", $username);
            $result->execute();
            $resultSet = $result->get_result();

            if($resultSet->num_rows > 0)
            {
              echo "<script>";
              echo 'document.querySelector(".error-content").textContent = "USER NOT AVAILABLE"';
              echo "</script>";
            }
            else
            {
              $hashed_password = password_hash($passwd, PASSWORD_DEFAULT);
  
              $query = " INSERT INTO users (name, password) VALUES (?, ?) ";
              $result = $conn->prepare($query);
              $result->bind_param("ss", $username, $hashed_password);
              $result->execute();
            }
            // $query = mysqli_query($conn," SELECT * FROM users WHERE name = '$username' AND password = '$passwd' ");
            // $row =  mysqli_fetch_array($query);
            
            // $conn->query($query);
            // PONEEEER
            // if($conn->query($query))
            //     echo "USUARIO REGISTRADO CORRECTAMENTE";
            // else
            //     echo "Error".$query."<br>".$conn->error;

        }
        // if(isset($_SESSION["username"]))
        // {
        //    header("Location:home.php");
        // }
    
?>
          <form action="index.php" method="post">
            <div class="field">
            <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="field">
            <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="field btn">
              <div class="btn-layer"></div>
              <input type="submit" name="register" value="register">
            </div>
            <div class="signup-link">Already have an account? <a href="">Login</a></div>
          </form>
        </div>
      </div>
    </div>

</body>
<script>
    const loginText = document.querySelector(".title-text .login");
      const loginForm = document.querySelector("form.login");
      const loginBtn = document.querySelector("label.login");
      const signupBtn = document.querySelector("label.signup");
      const signupLink = document.querySelector("form .signup-link a");
      signupBtn.onclick = (()=>{
        loginForm.style.marginLeft = "-50%";
        loginText.style.marginLeft = "-50%";
      });
      loginBtn.onclick = (()=>{
        loginForm.style.marginLeft = "0%";
        loginText.style.marginLeft = "0%";
      });
      signupLink.onclick = (()=>{
        signupBtn.click();
        return false;
      });
</script>
</html>