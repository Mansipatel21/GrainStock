<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$database = "sabjithela_db";

$NoImgFound = "images/NoImg_Found.png";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if ($conn) {
//    echo "Success";
  }else{
    die("Connection failed: " . mysqli_connect_error());

}
?>