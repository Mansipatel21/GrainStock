<?php
include 'connection.php';
//include 'session.php';
$UserID = $_SESSION["UserID"];
$Stock = $_POST["Quantity"];
$ProductID = $_POST["ProductID"];
$Unit = $_POST["Unit"];
$ProductSplit = explode("~", $ProductID);

$Select = "Select pro.productname, dis.stock, dis.createddate
from distributionstock dis Inner Join productmaster pro on pro.productid = dis.productid where dis.productid = '$ProductSplit[0]'
       and dis.vendorid='$UserID' 
ORDER BY dis.createddate DESC limit 1 ; ";
//echo $Select;

$result = mysqli_query($conn, $Select)or die(mysqli_error($conn));

if (mysqli_num_rows($result) != 0) {
    $rows = mysqli_fetch_array($result);

    if ($ProductSplit[2] == 'GM') {
        if ($Unit == 'KG') {
            $ConvertUnit = $Stock * 1000;
        } else if ($Unit == 'QUINTAL') {
            $ConvertUnit = $Stock * 100000;
        } else {
            $ConvertUnit = $Stock;
        }
    } else if ($ProductSplit[2] == 'QUINTAL') {
        if ($Unit == 'GM') {
            $ConvertUnit = $Stock / 100000;
        } else if ($Unit == 'KG') {
            $ConvertUnit = $Stock / 100;
        } else {
            $ConvertUnit = $Stock;
        }
    } else if ($ProductSplit[2] == 'KG') {
        if ($Unit == 'GM') {
            $ConvertUnit = $Stock / 1000;
        } else if ($Unit == 'QUINTAL') {
            $ConvertUnit = $Stock * 100;
        } else {
            $ConvertUnit = $Stock;
        }
    } else {
        $ConvertUnit = $Stock;
    }

    $Stock = $rows['stock'];
    if ($Stock >= $ConvertUnit) {
        echo "";
    } else {
        echo "Only $Stock $ProductSplit[2] available in stock!";
    }
} else {
    echo "Product is not available in stock...!!!Choose another Product!";
}
?>