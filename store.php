<?php
    require_once 'header2.php';
    include 'db_config.php';
    // session_start();
    if (!isset($_SESSION["username"]))
        header("location:index.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@1,700&family=Lato:ital,wght@1,700&display=swap");
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Courier Prime", monospace;
            font-family: "Lato", sans-serif;
        }
        .hr{
            border: 1px solid rgba(0,0,0,0.45);
            margin-bottom: 18px;
        }
        html, body {
            height: 100%;
            width: 100%;
            /*background-color: #8EC5FC;*/
            background-image: linear-gradient(62deg, #8EC5FC 0%, #E0C3FC 100%);
            background-attachment: fixed;
        }
        .container{
            /* background: blue; */
            margin: 80px auto;
            width: 95%;

        }
        .container-title{
            display: flex;
            padding: 12px 35px 12px 40px;
            justify-content: space-between;
        }
        .cart-box-first{
            display: flex;
            align-items: center;
        }
        .cart-box{
            height: 50px;
            width: 100%;
            display: flex;
            align-items: center;
            padding: 10px 30px;
            /* justify-content: space-between; */
        }
        
        .cart-box-img img{
            width: 30px;
            height: 30px;
            margin-right: 14px;
        }
        .w1, .p1{
            width: 40%;
        }
        .w2, .p2{
            width: 40%;
        }
        .w3, .p3{
            width: 8%;
        }
        .w4, .p4{
            width: 12%;
        }
        .add-to-cart{
            padding: 4px 12px;
            border-radius: 10px;
            margin-left: 20px;
        }
        .add-to-cart input{
            outline: none;
        }

        
    .cart{
        height: 100%;
        width: 30%;
        position: fixed;
        top: 0;
        left: 0;
        /* left: -100px; */
        z-index: -1;
        /* z-index: 1; */
        transform: translate(-100px);
        transition: transform 1s ease, opacity 1s ease; /* Transiciones para transform y opacity */
        opacity: 1;
        padding: 15px;
        background: rgb(255, 255, 255);
    }

    .blur{
        position: fixed;
        width: 100%;
        top: 0;
        left: 0;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: -1;
        opacity: 0;
        transition: opacity 1s ease;
    }
    .cart-top{
        margin-top: 20px;
    }
    .cart-list{
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 5px;
        overflow: auto;
    }
    h3{
        margin-left: 10px;
        padding-bottom: 6px;
    }

    hr{
        margin-bottom: 8px;
    }

    .list-item{
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 10px 10px 15px;
        border: 1px solid rgba(0,0,0,0.45);
        border-radius: 10px;
    }
    input{
        background: -webkit-linear-gradient(right, #003366, #004080, #0059b3, #0073e6);
        color: white;
        border: none;
        outline: none;
    }
    .item-coint1{
        width: 60%;
    }
    .field{
        padding: 6px 8px;
        border-radius: 5px;
    }
    .item-artist{
        margin-left: 5px;
        font-size: 14px;
    }

    .cart-button{
        margin: 0 auto;
        width: 100%;
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .field-cart{
        margin: 0 auto;
        padding: 10px 34px;
        border-radius: 8px;
    }
    </style>
</head>
<body>
    <?php
        //$query2 = "SELECT * FROM songs "; //query mostrar todas
        // $result = $conn->query($query);

        //query mostrar las que no estan compradas
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
            
            while ($row = $resultSet->fetch_assoc())
            {
                echo '<div class="cart-box">';
                echo '<div class="cart-box-first w1">';
                echo '<div class="cart-box-img">';
                echo '<img src="img/disk.png">';
                echo '</div>';
                echo '<p>' . htmlspecialchars($row["title"]) . '</p>';
                echo '</div>';
                echo '<p class="w2">' . htmlspecialchars($row["artist"]) . '</p>';
                echo '<p class="w3">' . htmlspecialchars($row["price"]) . '€</p>';
                echo '<div class="w4">';
                echo '<form action="store.php" method="post">';
                echo '<input type="hidden" name="song_id" value="' . $row["song_id"] . '">';
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
    <!-- <div class="blur" onclick="closecart()"></div> -->
        <!-- cart -->

    <?php 
    // include 'db_config.php';
    // session_start();
    // if (!isset($_SESSION["username"]))
    //     header("location:index.php");
    // function cart($conn)
    // {
    //         echo "<script>
    //     document.querySelector('.cart-list').innerHTML = '';
    //   </script>";
    //     $query = "
    //     SELECT user_cart.song_id, songs.title, songs.artist, songs.price
    //     FROM user_cart
    //     INNER JOIN songs ON user_cart.song_id = songs.song_id
    //     WHERE user_id = ?";
        
    //     $result = $conn->prepare($query);
    //     $result->bind_param("i", $_SESSION["id"]);
    //     $result->execute();
    //     $resultSet = $result->get_result();
        
    //     if($resultSet->num_rows > 0)
    //     {
    //         echo '<div class="cart">';
    //         echo '<div class="close" onclick="closecart()">';
    //             echo '<p>X</p>';
    //         echo '</div>';
    //             echo '<div class="cart-top">';
    //                 echo '<h3>Cart</h3>';
    //                  echo '<hr>';
    //              echo '</div>';
    //             echo '<div class="cart-list">';
    //         while ($row = $resultSet->fetch_assoc())
    //         {
    //                 echo '<div class="list-item">';
    //                     echo '<div class="item-coint1">';
    //                         echo '<p>' . htmlspecialchars($row["title"]) . '</p>';
    //                         echo '<p class="item-artist">' . htmlspecialchars($row["artist"]) . '</p>';
    //                     echo '</div>';
    //                     echo '<p class="item-coint2">' . htmlspecialchars($row["price"]) . '€</p>';
                
    //                     echo '<div class="item-coint3">';
    //                         echo '<form action="store.php" method="post">';
    //                             echo '<input type="hidden" name="song_id" value="' . $row["song_id"] . '">';
    //                             echo '<input type="submit" class="field" name="delete_cart" value="X">';
    //                         echo '</form>';
    //                     echo '</div>';
    //                 echo '</div>';
    //         }
    //             echo '</div>';
    //             echo '<div class="cart-button">';
    //                 echo '<form action="store.php" method="post">';
    //         // echo '<input type="hidden" name="song_id" value="' . $row["song_id"] . '">';
    //                 echo '<input type="submit" class="field-cart" name="buy" value="Buy">';
    //                 echo '</form>';
    //             echo '</div>';
    //         echo '</div>';
    //     }
    // }
    //     if(!isset($_POST['buy']))
    //         cart($conn);

    //     if(isset($_POST['delete_cart']))
    //     {  
    //         $query = "DELETE FROM user_cart WHERE user_id = ? AND song_id = ?";
    //         $result = $conn->prepare($query);
    //         $result->bind_param("ii", $_SESSION["id"], $_POST['song_id']);
    //         $result->execute();
            
    //         cart($conn);

    //     }

    //     if(isset($_POST['buy']))
    //     {  
    //         $query = "
    //         SELECT *
    //         FROM user_cart
    //         WHERE user_id = ?";

    //         $result = $conn->prepare($query);
    //         $result->bind_param("i", $_SESSION["id"]);
    //         $result->execute();
            
    //         $resultSet = $result->get_result();

    //         if($resultSet->num_rows > 0)
    //         {
    //             while ($row = $resultSet->fetch_assoc())
    //             {
    //                 $query = "INSERT INTO user_songs (user_id, user_song) VALUES (?, ?)";

    //                 $result = $conn->prepare($query);
    //                 $result->bind_param("ii", $_SESSION["id"], $row['song_id']);
    //                 $result->execute();

    //                 $query = "DELETE FROM user_cart WHERE user_id = ? AND song_id = ?";
    //                 $result = $conn->prepare($query);
    //                 $result->bind_param("ii", $_SESSION["id"], $row['song_id']);
    //                 $result->execute();
    //             }
    //             echo "<script>
    //             document.querySelector('.cart-list').innerHTML = '';
    //           </script>";
    //         }

    //     }

    ?>

    <!-- <div class="cart">
        <div class="cart-top">
            <h3>Cart</h3>
            <hr>
        </div>
        <div class="cart-list">
            <div class="list-item">
                <div class="list1">
                    <p>Imagine</p>
                    <p>John Lenon</p>
                </div>
                <p>1.19€</p>
                <button>X</button>
            </div>
        </div>
    </div> -->
        <script>
            function showcart()
            {
                var cart = document.querySelector('.cart');
                var blur = document.querySelector('.blur');
                cart.style.zIndex = 3
                blur.style.zIndex = 3
                // Agregar el div al cuerpo del documento
                /* <div class="blur" onclick="closecart()"></div> */
                    // cart.style.transform = 'translateX(100px)';
                    cart.style.opacity = 1
                    blur.style.opacity = 1
                    
            }
        
            function closecart()
            {
                var cart = document.querySelector('.cart');
                var blur = document.querySelector('.blur');
                
                // cart.style.transform = 'translateX(-100px)';
                cart.style.opacity = 0
                blur.style.opacity = 0
                setTimeout(() => {
                    cart.style.zIndex = -1
                    blur.style.zIndex = -1
                    
                }, 700);
            }
        </script>
</body>
</html>

<!-- Mostrar una lista de canciones disponibles para la compra en la tienda.

Cada canción debe tener un título, artista y precio.

Permitir a los usuarios agregar canciones al carrito de compras. -->