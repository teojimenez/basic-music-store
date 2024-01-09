<?php
    include 'db_config.php';
    require_once 'header.php';
    customHeader(1);
    if (!isset($_SESSION["username"]))
        header("location:index.php");

    function cart($conn)
    {
        echo "<script>
        document.querySelector('.cart-list').innerHTML = '';
      </script>";
        echo "<script>
        document.querySelector('.cart-old').innerHTML = '';
      </script>";
        
        $query = "
        SELECT user_cart.song_id, songs.title, songs.artist, songs.price
        FROM user_cart
        INNER JOIN songs ON user_cart.song_id = songs.song_id
        WHERE user_id = ?";
        
        $result = $conn->prepare($query);
        $result->bind_param("i", $_SESSION["id"]);
        $result->execute();
        $resultSet = $result->get_result();
        
        if($resultSet->num_rows > 0)
        {

            echo "<script>";
            echo "var div = document.querySelector('.cart-old');";
            echo "if (div) {";
            echo "    document.body.removeChild(div);";
            echo "}";
            echo "</script>";
            echo "<script>";
            echo "var div2 = document.querySelector('.cart');";
            echo "if (div2) {";
            echo "    document.body.removeChild(div2);";
            echo "}";
            echo "</script>";

            echo '<div class="cart">';
                echo '<div class="cart-top">';
                    echo '<h3>Cart</h3>';
                     echo '<hr>';
                 echo '</div>';
                echo '<div class="cart-list">';
            while ($row = $resultSet->fetch_assoc())
            {
                    echo '<div class="list-item">';
                        echo '<div class="item-coint1">';
                            echo '<p>' . htmlspecialchars($row["title"]) . '</p>';
                            echo '<p class="item-artist">' . htmlspecialchars($row["artist"]) . '</p>';
                        echo '</div>';
                        echo '<p class="item-coint2">' . htmlspecialchars($row["price"]) . '€</p>';
                
                        echo '<div class="item-coint3">';
                            echo '<form action="cart.php" method="post">';
                                echo '<input type="hidden" name="song_id" value="' . $row["song_id"] . '">';
                                echo '<input type="submit" class="field" name="delete_cart" value="X">';
                            echo '</form>';
                        echo '</div>';
                    echo '</div>';
            }
                echo '</div>';
                echo '<div class="cart-button">';
                    echo '<form action="cart.php" method="post">';
            // echo '<input type="hidden" name="song_id" value="' . $row["song_id"] . '">';
                    echo '<input type="submit" class="field-cart" name="buy" value="Buy">';
                    echo '</form>';
                echo '</div>';
            echo '</div>';
        }
        else
        {
            echo "<script>";
            echo 'document.querySelector(".cart-old").innerHTMl = ""';
            echo "</script>";
            
            echo '<div class="cart-old cart">';           
            echo '<h4>(No songs avalaible in the cart)</h4>';
            echo '</div>';        
            // <div class="main-old main">
            //     <p class="main-title">List of Songs: </p>
            //     <div class="songs"></div>
            // </div>
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/cart.css">
    <title>Document</title>
</head>
<body>
    <?php
        if(!isset($_POST['buy']))
            cart($conn);

        if(isset($_POST['delete_cart']))
        {  
            $query = "DELETE FROM user_cart WHERE user_id = ? AND song_id = ?";
            $result = $conn->prepare($query);
            $result->bind_param("ii", $_SESSION["id"], $_POST['song_id']);
            $result->execute();
            
            cart($conn);

        }

        if(isset($_POST['buy']))
        {  
            $query = "
            SELECT *
            FROM user_cart
            WHERE user_id = ?";

            $result = $conn->prepare($query);
            $result->bind_param("i", $_SESSION["id"]);
            $result->execute();
            
            $resultSet = $result->get_result();

            if($resultSet->num_rows > 0)
            {

                while ($row = $resultSet->fetch_object())
                {
                    $query = "INSERT INTO user_songs (user_id, user_song) VALUES (?, ?)";

                    $result = $conn->prepare($query);
                    $result->bind_param("ii", $_SESSION["id"], $row->song_id);
                    $result->execute();

                    $query = "DELETE FROM user_cart WHERE user_id = ? AND song_id = ?";
                    $result = $conn->prepare($query);
                    $result->bind_param("ii", $_SESSION["id"], $row->song_id);
                    $result->execute();
                }
                echo "<script>
                    document.querySelector('.cart-list').innerHTML = '';
                </script>";
            }
        }
    ?>
</body>
</html>
