<?php

include 'connection.php';
$ProductID = $_POST["ProductID"];
$ProductSplit = explode("~", $ProductID);
$select = "Select pro.productname, buy.stock, buy.createddate
from inwardproduct buy Inner Join productmaster pro on pro.productname = buy.productid where buy.productid = '$ProductSplit[0]' 
ORDER BY buy.createddate DESC limit 1;";
//echo $select;
$result = mysqli_query($conn, $select)or die(mysqli_error($conn));
if (mysqli_num_rows($result) != 0) {
    $row = mysqli_fetch_array($result);
    $Stock = $row['stock'];
    $productname = $row['productname'];
    
    if ($Stock == 0) {
        echo "0~$productname is out of stock. Please Choose another Product.!";
    }
}
?>