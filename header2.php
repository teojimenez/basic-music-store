<?php
    session_start();
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
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@1,700&family=Lato:ital,wght@1,700&display=swap');

*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Courier Prime', monospace;
    font-family: 'Lato', sans-serif;
}
    .div1 p{
        font-size: 20px;
    }
    header{
        width: 95%;
        /* height: 50px; */
        height: 65px;
        /* background: blue; */
        margin: 18px auto;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        padding: 28px 20px;
        border-radius: 15px;
        color: white;
        background: -webkit-linear-gradient(right, #003366, #004080, #0059b3, #0073e6);
        box-shadow: rgb(38, 57, 77) 0px 20px 30px -10px;
}

.div2 a{
    padding: 2px 14px;
    font-size: 17px;
    color: white;
    text-decoration: none;
}
.div1{
    display: flex;
}
.div2{
    display: flex;
}
.name{
    margin-left: 8px;
}

.right{
    border-right: 1px solid rgba(255,255,255,0.6);
}
    </style>
</head>
<body>
    <header>
        <div class="div1">
            <p>       <p class="name"></p></p>
        </div>
        <div class="div2">
            <a href="home.php" class="right">Home</a>
            <a href="store.php" class="right">Store</a>
            <!-- <a href="" class="right"  onclick="showcart()">Cart</a> -->
            <a href="cart.php" class="right" >Cart</a>
            <a href="logout.php">Log Out</a>
        </div>
    </header>
</body>
</html>