<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);

$host = "localhost";
$username = "root";
$password = "";
$database = "user_management";

$conn = mysqli_connect("$host" , "$username" , "$password" , "$database");

if($conn){
   // echo "connection successful<br>";
} else{
    die("connection failed" . mysqli_connect_error());
}


?>