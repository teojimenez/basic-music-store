<?php
    require_once 'header.php';
    include 'db_config.php';
    customHeader(0);
    if (!isset($_SESSION["username"]))
        header("location:index.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="styles/home.css">
    <title>Document</title>
</head>
<body>
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
        while ($row = $resultSet->fetch_object())
        {
            echo '<div class="box">';
            echo '<img src="img/disk.png">';
            echo '<div class="content">';
            echo '<p class="box-title">' . htmlspecialchars($row->title) . '</p>';
            echo '<p class="box-artist">' . htmlspecialchars($row->artist) . '</p>';
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
    }
    ?>
</body>
</html>