<?php

require 'connection.php';
        if ($_POST["type"] == "Unit_Delete") {
     $UnitID = $_POST['UnitID'];
     $Unit_Insert_qry = "Delete from unitmaster where unitid='$UnitID';";
    
     $Query_Result = mysqli_query($conn, $Unit_Insert_qry) or die(mysqli_error($conn));
         $Mess = "1";
        }
        
        if ($_POST["type"] == "Category_Delete") {
     $categoryid = $_POST['categoryid'];
     $Category_Insert_qry = "Delete from categorymaster where categoryid='$categoryid';";
    
     $Query_Result = mysqli_query($conn, $Category_Insert_qry) or die(mysqli_error($conn));
         $Mess = "1";
        }
        
        if ($_POST["type"] == "Product_Delete") {
     $ProductID = $_POST['ProductID'];
     $Product_Insert_qry = "Delete from productmaster where productid='$ProductID';";
    
     $Query_Result = mysqli_query($conn, $Product_Insert_qry) or die(mysqli_error($conn));
         $Mess = "1";
        }
        
       if ($_POST["type"] == "Role_Delete") {
     $roleid = $_POST['roleid'];
     $Role_Insert_qry = "Delete from rolemaster where roleid='$roleid';";
    
     $Query_Result = mysqli_query($conn, $Role_Insert_qry) or die(mysqli_error($conn));
         $Mess = "1";
        } 
        
       if ($_POST["type"] == "User_Delete") {
     $userid = $_POST['userid'];
     $User_Insert_qry = "Delete from userdetails where userid='$userid';";
    
     $Query_Result = mysqli_query($conn, $User_Insert_qry) or die(mysqli_error($conn));
         $Mess = "1";
        } 
        
        if ($_POST["type"] == "User_ActivateDetactive") {
    $UserID = $_POST['UserID'];
    $ActivateType = $_POST['ActivateType'];

//    if ($ActivateType == "DeActivate") {
//        $UserActivate_Insert_qry = "UPDATE userdetails SET isactive='1', updateddate=current_timestamp()"
//                . " WHERE userid='$UserID'";
//
//        $Query_Result = mysqli_query($conn, $UserActivate_Insert_qry) or die(mysqli_error($conn));
//        $Mess = "You want to DeActivate these account";
//    } else {
//        $UserActivate_Insert_qry = "UPDATE userdetails SET isactive='0', updateddate=current_timestamp()"
//                . " WHERE userid='$UserID'";
//
//        $Query_Result = mysqli_query($conn, $UserActivate_Insert_qry) or die(mysqli_error($conn));
//        $Mess = "You want to Active these account";
//    }


    if ($ActivateType == "DeActivate") {
        $Mess = "1";
        $IsAcive = "1";
    } else {
        $Mess = "0";
        $IsAcive = "0";
    }
    $UserActivate_Insert_qry = "UPDATE userdetails SET isactive='$IsAcive', updateddate=current_timestamp()"
            . " WHERE userid='$UserID'";
    $Query_Result = mysqli_query($conn, $UserActivate_Insert_qry) or die(mysqli_error($conn));
    echo $Mess;
}
?>