<?php

$servername = "localhost";
$username = "root";
$password = "root";

$conn;

try{
    $conn = new PDO("mysql:host=$servername;dbname=hackathon", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "Connection Failed: " . $e->getMessage();
}

?>
