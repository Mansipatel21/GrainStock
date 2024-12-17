<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Calcutta');
include 'connection.php';
//$URL = "";

if (isset($_POST["btnSubmit"])) {

    $BuyProcessID = $_POST['hdnBuyProcessID'];
    $Product = $_POST['ddlProduct'];
    $Unit = $_POST['ddlUnit'];
    $Quantity = $_POST['txtQuantity'];
    $BuyPrice = $_POST['txtBuyPrice'];
    $UserID = $_SESSION["UserID"];

    $ProductSplit = explode("~", $Product);

    $Select = "Select productid,margin From productmaster Where productid = '$ProductSplit[0]'; ";
    // echo $Select;
    $result = mysqli_query($conn, $Select)or die(mysqli_error($conn));
    $rows = mysqli_fetch_array($result);
    $productid = $rows['productid'];
    $Margin = $rows['margin'];

    $PercentageMargin = $BuyPrice * $Margin / 100;
    $SellPrice = $BuyPrice + $PercentageMargin;
//    echo $SellPrice;


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
    
    $Query = "INSERT INTO inwardproduct(inwardproductid, userid, productid, quantity,"
            . "unit, buyprice, sellprice, inward, outward, stock)"
            . "select UUID(),'$UserID','$ProductSplit[0]','$ConvertUnit',"
            . "'$ProductSplit[1]', '$BuyPrice','$SellPrice','$ConvertUnit', 0, '$ConvertUnit'";
//    echo $Query;
    $Query_result = mysqli_query($conn, $Query)or die(mysqli_error($conn));

    echo "<script> alert ('Stock is Successfully Inserted...!!!');window.location.replace('buyprocess.php');</script>";
    //$URL = "buyprocess.php";
    //echo $Dialog;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <style>
            #trying #ddlDorpdown .btn{
                border: 1px solid;
            }
        </style>
    </head>

    <body class="theme-green">
        <?php
        include('header.php');
        ?>
        <section class="content">
            <div class="container-fluid">
                <div class="block-header">
                    <!--<h2>Add Unit</h2>-->
                </div>
                <!-- Input -->
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2 id="Card_BuyProcess">
                                    Central Warehouse Inward
                                </h2>
                            </div>
                            <div class="body">
                                <form id="d" class="form_validation" method="POST" enctype="multipart/form-data" 
                                      onsubmit="return ValBuyProcess(this);">
                                    <div class="row clearfix">
                                        <div class="col-sm-12">                            
                                            <div class="col-sm-6">  
                                                <div class="form-group">
                                                    <input type="hidden" name="hdnBuyProcessID" id="hdnBuyProcessID"/>
                                                    <select class="form-control show-tick" name="ddlProduct" id="ddlProduct">
                                                        <option value="-1">-- Select Product --</option>
                                                        <?php
                                                        $Select = mysqli_query($conn, "SELECT productid,productname,isactive,unit"
                                                                . " FROM productmaster WHERE isactive='1'");
                                                        while ($row = mysqli_fetch_array($Select)) {
                                                            $ProUnit = $row["unit"];
                                                            ?>
                                                            <option value="<?php
                                                            echo $row["productname"];
                                                            echo "~" . $ProUnit;
                                                            ?>">
                                                                <?php echo $row["productname"]; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">                            
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="txtQuantity" id="txtQuantity"
                                                               class="form-control" placeholder="Quantity"/>
                                                    </div>
                                                    <span id="invalid1_text" class="text-danger" style="display:none;">* Quantity must be integer.</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <select class="form-control show-tick" name="ddlUnit" id="ddlUnit">
                                                        <option value="-1">-- Select Unit --</option>
                                                        <?php
                                                          $Select_Unit = mysqli_query($conn, "SELECT unitid, unit FROM unitmaster");
                                                          while ($rows = mysqli_fetch_array($Select_Unit)) {
                                                          ?>
                                                          <option value="<?php echo $rows["unit"]; ?>">
                                                             <?php echo $rows["unit"]; ?></option>
                                                           <?php
                                                           }
                                                          ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">                            
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="txtBuyPrice" id="txtBuyPrice"
                                                               class="form-control" placeholder="Price"/>
                                                    </div>
                                                    <span id="invalid_text" class="text-danger" style="display:none;">* Price must be integer.</span>
                                                </div>
                                            </div>
                                        </div>
                                        <script>

                                            function ValBuyProcess(frm) {

                                                if (frm.ddlProduct.value == "-1") {
                                                    var btn = $("button[data-id='ddlProduct']");
                                                    btn.addClass("invalid");
                                                    return false;
                                                } else {
                                                    var btn = $("button[data-id='ddlProduct']");
                                                    btn.removeClass("invalid");
                                                }

                                                var textVal1 = txtQuantity.value;
                                                var regex1 = /^\d+(?:[.,]\d+)*$/;
                                                var passed1 = textVal1.match(regex1);
                                                if (passed1 == null) {
//                                                if (frm.txtQuantity.value == "") {
                                                    frm.txtQuantity.classList.add('invalid');
                                                    document.getElementById("invalid1_text").style.display = 'block';
                                                    return false;
                                                } else {
                                                    frm.txtQuantity.classList.remove('invalid');
                                                    document.getElementById("invalid1_text").style.display = 'none';
                                                }

                                                if (frm.ddlUnit.value == "-1") {
                                                    var btn = $("button[data-id='ddlUnit']");
                                                    btn.addClass("invalid");
                                                    return false;
                                                } else {
                                                    var btn = $("button[data-id='ddlUnit']");
                                                    btn.removeClass("invalid");
                                                }


                                                var textVal = txtBuyPrice.value;
                                                var regex = /^\d+(?:[.,]\d+)*$/;
                                                var passed = textVal.match(regex);
                                                if (passed == null) {
//                                                    if (frm.txtBuyPrice.value == "") {
                                                    frm.txtBuyPrice.classList.add('invalid');
                                                    document.getElementById("invalid_text").style.display = 'block';
                                                    return false;
                                                } else {
                                                    frm.txtBuyPrice.classList.remove('invalid');
                                                    document.getElementById("invalid_text").style.display = 'none';
                                                }
                                            }

                                            // $(document).ready(function () {
                                            function Cancle() {
                                                //    $("input").click(function () {
                                                $("#d")[0].reset();
                                                document.getElementById("ddlProduct").selectedIndex = 0;
                                                var index = 0;
                                                var btn = $("button[data-id='ddlProduct']");
                                                var catname = $("#ddlProduct")[0].selectedOptions[0].text;
                                                var li = $("#ddlProduct").siblings("div").children().children("li[data-original-index='" +
                                                        index + "']");
                                                li.addClass("selected");
                                                li.siblings().removeClass();

                                                btn[0].setAttribute("title", catname);
                                                btn.children()[0].innerText = catname;

                                                document.getElementById("ddlUnit").selectedIndex = 0;
                                                var index = document.getElementById("ddlUnit").selectedIndex;
                                                var btn = $("button[data-id='ddlUnit']");
                                                var catname = $("#ddlUnit")[0].selectedOptions[0].text;
                                                var li = $("#ddlUnit").siblings("div").children().children("li[data-original-index='" +
                                                        index + "']");
                                                li.addClass("selected");
                                                li.siblings().removeClass();

                                                btn[0].setAttribute("title", catname);
                                                btn.children()[0].innerText = catname;

                                                document.getElementById("Card_BuyProcess").innerHTML = " Central Warehouse Inward";
                                                document.getElementById("btnSubmit").value = "Add";
                                                //  });
                                            }
                                            //                                            });
                                        </script>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-sm-3">
                                                    <input class="btn btn-block bg-green waves-effect" 
                                                           id="btnSubmit" name="btnSubmit" type="submit" value="Add"/>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="button" class="btn btn-block bg-green waves-effect" 
                                                           value="Cancle" onclick="Cancle();"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix" id="trying">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>Warehouse Inward Details</h2>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                            <thead>
                                                <tr><th>#</th>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Buy Price</th>
                                                    <th>Sell Price</th>
                                                    <th>Inward</th>
                                                    <th>Outward</th>
                                                    <th>Stock</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $Select_Product = "SELECT  pro.productid, war.productid, war.quantity, war.unit,"
                                                            . " war.buyprice, war.sellprice, war.inward, war.outward, war.stock,"
                                                            . " war.createddate, pro.productname FROM inwardproduct war"
                                                            . " inner join productmaster pro ON pro.productname = war.productid;";
                         //       echo $Select_Product; //die();
                                                    $result_query = mysqli_query($conn, $Select_Product) or die(mysqli_error($conn));

                                                    $count = 1;
                                                    while ($rows = mysqli_fetch_array($result_query)) {
                                                        $ProductID = $rows['productid'];
                                                        $ProductName = $rows['productname'];
                                                        $Quantity = $rows['quantity'];
                                                        $Unit = $rows['unit'];
                                                        $BuyPrice = $rows['buyprice'];
                                                        $SellPrice = $rows['sellprice'];
                                                        $Inward = $rows['inward'];
                                                        $Outward = $rows['outward'];
                                                        $Stock = $rows['stock'];
                                                        $CreatedDate = $rows['createddate'];

                                                        $da = strtotime($CreatedDate);
                                                        $Date = date('Y/m/d', $da);

                                                        echo ' <tr>
                                                        <td>' . $count . '</td>
                                                        <td>' . $ProductName . '</td>
                                                        <td>' . $Quantity . '</td>
                                                        <td>' . $Unit . '</td>
                                                        <td>' . $BuyPrice . '</td>
                                                        <td>' . $SellPrice . '</td>
                                                        <td>' . $Inward . '</td>
                                                        <td>' . $Outward . '</td>
                                                        <td>' . $Stock . '</td>
                                                        <td>' . $Date . '</td></tr>';
                                                       $count++;
                                                    }
                                                
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
