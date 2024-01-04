<?php 
    session_start();
    //LO EJECUTA, entonces es true y destruye la session
    if(session_destroy())
        header("location:index.php");
?>