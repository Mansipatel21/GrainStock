<?php

include 'connection.php';
//include 'session.php';
$UserID = $_SESSION["UserID"];
$ProductID = $_POST["ProductID"];
$ProductSplit = explode("~", $ProductID);
$select = "select dis.productid, dis.stock, dis.unit , pro.productname from "
        . "distributionstock dis Inner Join productmaster pro on pro.productid = dis.productid"
        . " where dis.productid='$ProductSplit[0]' and dis.vendorid='$UserID';";
//echo $select;
$result = mysqli_query($conn, $select)or die(mysqli_error($conn));
if (mysqli_num_rows($result) != 0) {
    $row = mysqli_fetch_array($result);
    $Stock = $row['stock'];
    $productname = $row['productname'];

    if ($Stock == 0) {
        echo "$productname is out of stock. Please Choose another Product.!";
    } else {
        echo "";
    }
} else {
    echo "These Product is not in the Stock...Please Choose another Product.!";
}
?>
