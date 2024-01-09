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
    <link rel="stylesheet" href="styles/index.css">
    <style>
      .error-content{
        color: red;
        position: absolute;
        top: 10%;
      }
    </style>
    <title>LOG IN / SIGN UP</title>
</head>
<body>
    <h4 class="error-content"></h4>
    <div class="wrapper">
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
            <div class="field">
              <input type="text" placeholder="Name" name="username" required>
            </div>
            <div class="field">
              <input type="password" placeholder="Password" name="password" required>
            </div>
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

            if($resultSet->num_rows > 0)
            {
                $row = $resultSet->fetch_object(); //split
                if(password_verify($password . $row->salt,  $row->password))
                {
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $password;
                    $_SESSION['id'] = $row->id;
                }
                else
                {
                  echo "<script>";
                  echo 'document.querySelector(".error-content").textContent = "USER OR PASSWORD WRONG"';
                  echo "</script>";
                }
            }
            $resultSet->close();
        }
        else if  (isset($_POST['register']))
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
              $salt = bin2hex(random_bytes(16));
              $hashed_password = password_hash($passwd . $salt, PASSWORD_BCRYPT);
              $query = " INSERT INTO users (name, password, salt) VALUES (?, ?, ?) ";
              $result = $conn->prepare($query);
              $result->bind_param("sss", $username, $hashed_password, $salt);
              $result->execute();
            }
        }

        if (isset($_SESSION["username"]))
          header("Location:home.php");
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
</body>
</html>