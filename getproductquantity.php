<?php

include 'connection.php';
$Quantity = $_POST["Quantity"];
$ProductID = $_POST["ProductID"];
$Unit = $_POST["Unit"];
$ProductSplit = explode("~", $ProductID);
$Select = "Select pro.productname, buy.stock, buy.createddate,buy.quantity
from inwardproduct buy Inner Join productmaster pro on pro.productname = buy.productid where buy.productid = '$ProductSplit[0]' 
ORDER BY buy.createddate DESC limit 1; ";
//echo $Select;

$result = mysqli_query($conn, $Select)or die(mysqli_error($conn));

if(mysqli_num_rows($result)!=0){
$rows = mysqli_fetch_array($result);

    if ($ProductSplit[1] == 'GM') {
        if ($Unit == 'KG') {
            $ConvertUnit = $Quantity * 1000;
        } else if ($Unit == 'QUINTAL') {
            $ConvertUnit = $Quantity * 100000;
        } else {
            $ConvertUnit = $Quantity;
        }
    } else if ($ProductSplit[1] == 'QUINTAL') {
        if ($Unit == 'GM') {
            $ConvertUnit = $Quantity / 100000;
        } else if ($Unit == 'KG') {
            $ConvertUnit = $Quantity / 100;
        } else {
            $ConvertUnit = $Quantity;
        }
    } else if ($ProductSplit[1] == 'KG') {
        if ($Unit == 'GM') {
            $ConvertUnit = $Quantity / 1000;
        } else if ($Unit == 'QUINTAL') {
            $ConvertUnit = $Quantity * 100;
        } else {
            $ConvertUnit = $Quantity;
        }
    } else {
        $ConvertUnit = $Quantity;
    }

    $Stock = $rows['stock'];
    $Quantitychk = $rows['quantity'];
    
    
    if ($Stock >= $ConvertUnit) {
        echo "";
    } else {
        echo "Only $Stock $ProductSplit[1] available in stock!";
    }

}
else{
     echo "Product is not available in stock...!!!Choose another Product!";
}
?>