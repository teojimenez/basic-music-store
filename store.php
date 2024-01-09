<?php
    require_once 'header.php';
    include 'db_config.php';
    customHeader(1);
    if (!isset($_SESSION["username"]))
        header("location:index.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/store.css">
    <title>Document</title>
</head>
<body>
    <?php
        // Todas las canciones menos las que tenga el usuario
        $query = "
        SELECT *
        FROM songs
        WHERE song_id NOT IN (
            SELECT user_song
            FROM user_songs
            WHERE user_id = ?
        ); ";
        
        $result = $conn->prepare($query);
        $result->bind_param("i", $_SESSION["id"]);
        $result->execute();
        $resultSet = $result->get_result();
        
        
        if($resultSet->num_rows > 0)
        {
            echo '<div class="container">';
            echo '<div class="container-title">';
            echo '<p class="p1">Title</p>';
            echo '<p class="p2">Artist</p>';
            echo '<p class="p3">Price</p>';
            echo '<p class="p4">Add to Cart</p>';
            echo '</div>';
            echo '<hr class=".hr">';
            
            while ($row = $resultSet->fetch_object())
            {
                echo '<div class="cart-box">';
                echo '<div class="cart-box-first w1">';
                echo '<div class="cart-box-img">';
                echo '<img src="img/disk.png">';
                echo '</div>';
                echo '<p>' . htmlspecialchars($row->title) . '</p>';
                echo '</div>';
                echo '<p class="w2">' . htmlspecialchars($row->artist) . '</p>';
                echo '<p class="w3">' . htmlspecialchars($row->price) . '€</p>';
                echo '<div class="w4">';
                echo '<form action="store.php" method="post">';
                echo '<input type="hidden" name="song_id" value="' . $row->song_id . '">';
                echo '<input type="submit" class="add-to-cart" name="add_cart" value="✓">';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
        
        if(isset($_POST['add_cart']))
        {  
            $query = "SELECT * FROM user_cart WHERE user_id = ? AND song_id = ? ";
            $result = $conn->prepare($query);
            $result->bind_param("ii", $_SESSION["id"], $_POST['song_id']);
            $result->execute();
            $resultSet = $result->get_result();
            if($resultSet->num_rows == 0)
            {
                $query = "INSERT INTO user_cart (user_id, song_id) VALUES (?, ?)";
                $result = $conn->prepare($query);
                $result->bind_param("ii", $_SESSION["id"], $_POST['song_id']);
                $result->execute();
            }
        }

    ?>
</body>
</html>

<!-- Mostrar una lista de canciones disponibles para la compra en la tienda.

Cada canción debe tener un título, artista y precio.

Permitir a los usuarios agregar canciones al carrito de compras. -->