<?php
    require_once 'header.php';
    include 'db_config.php';
    // session_start();
    if (!isset($_SESSION["username"]))
        header("location:index.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
    <style>

    @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@1,700&family=Lato:ital,wght@1,700&display=swap');

    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Courier Prime', monospace;
        font-family: 'Lato', sans-serif;
    }

    html{
        background-image: linear-gradient(62deg, #8EC5FC 0%, #E0C3FC 100%);
        background-attachment: fixed;
        height: 100vh;
    }
        .box{
            background-color: blue;
            width: 155px;
            height: 155px;
            position: relative;
            border-radius: 15px;
            /* border: 1px solid rgba(0,0,0,0.3); */
        }
        img{
            width: 100%;
            height: 100%;
            border-radius: 15px;
        }
        .content{
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100%;
            padding: 8px;
            height: 45%;
            background: rgba(199,205,201, 0.8);
            /* filter: blur(10px); */
            color: black;
            border-top: none;
            border-radius: 0 0 15px 15px;
        }
        .box-artist{
            margin-left: 6px;
            font-size: 14px;
        }
        .main{
            width: 95%;  
            margin: 18px auto;
            /* background: -webkit-linear-gradient(right, #003366, #004080, #0059b3, #0073e6); */
            border-radius: 15px;
            margin-top: 80px;
            box-shadow: rgb(38, 57, 77) 0px 20px 30px -10px;
            min-height: 460px;
        }
        .main-title{
            border-radius: 15px 15px 0 0;
            padding: 14px 20px;
            font-size: 18px;
            background: -webkit-linear-gradient(right, #003366, #004080, #0059b3, #0073e6);
        }
        .songs{
            width: 100%;
            height: 85%;
            /* background: white; */
            background: transparent;
            padding: 12px;
            /* border-radius: 0 0 15px 15px; */
            border-radius: 15px;
            /* flex-direction: row; */
            /* align-items: center; */
            /* justify-content: space-between; */
            display: flex;
            /* justify-content: flex-start; */
            justify-content: center;         align-items: center;
            flex-wrap: wrap;
            gap: 18px;
    }

    .songs-old{
        margin-top: 80px;
    }
    </style>
</head>
<body>
    <!-- <h2>Welcome</h2>
    <p>Click Here to <a href="logout.php">Log Out</a></p><br>
    <p>Store<a href="store.php">CLICK</a></p><br>
    <br>
    <br> -->
    <?php

    $query = "
    SELECT *
    FROM user_songs
    INNER JOIN songs ON user_songs.user_song = songs.song_id
    WHERE user_id = ?";

    $result = $conn->prepare($query);
    $result->bind_param("i", $_SESSION["id"]);
    $result->execute();
    $resultSet = $result->get_result();

    if($resultSet->num_rows > 0)
    {
        echo "<script>";
        echo "var div = document.querySelector('.main-old');";
        echo "if (div) {";
        echo "    document.body.removeChild(div);";
        echo "}";
        echo "</script>";
        
        echo '<div class="main">';
        echo '<p class="main-title">List of Songs: </p>';
        echo '<div class="songs">';
        while ($row = $resultSet->fetch_assoc())
        {
            echo '<div class="box">';
            echo '<img src="img/disk.png">';
            echo '<div class="content">';
            echo '<p class="box-title">' . htmlspecialchars($row["title"]) . '</p>';
            echo '<p class="box-artist">' . htmlspecialchars($row["artist"]) . '</p>';
            // echo '<p>Price: ' . htmlspecialchars($row["price"]) . '€</p>';
            // echo '<form action="cart.php" method="post">';
            // echo '<input type="hidden" name="song_id" value="' . $row["song_id"] . '">';
            // echo '<input type="submit" class="field" name="delete_cart" value="delete_cart">';
            // echo '</form>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    }
    else
    {
        echo '<div class="main-old main">';        
        echo '<p class="main-title">List of Songs: </p>';        
        echo '<div class="songs-old songs">';
        echo '<h4>(No songs avalaible)</h4>';
        echo '</div>';
        echo '</div>';        
        // <div class="main-old main">
        //     <p class="main-title">List of Songs: </p>
        //     <div class="songs"></div>
        // </div>
    }

    
    ?>
</body>
</html>