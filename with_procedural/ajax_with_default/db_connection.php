<?php
    $host_name = "localhost";
    $user_name = "root";
    $password = "";
    $db_name = "CRUD_procedural_ajax_core";

    $conn = mysqli_connect($host_name, $user_name, $password, $db_name);
    if(!$conn){
        die("DB connection failed!".mysqli_connect_error($conn));
    }

?>