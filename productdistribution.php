<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Calcutta');
include 'connection.php';

$UserID = $_SESSION["UserID"];
//$URL = "";
//InwardMsg = "";
if (isset($_POST["btnSubmit"])) {

    $Vendor = $_POST['ddlVendor'];
    $Product = $_POST['ddlProduct'];
    $Quantity = $_POST['txtQuantity'];
    $Unit = $_POST['ddlUnit'];

    $ProductSplit = explode("~", $Product);

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

//  Nearest inword with outword 0

    $Select = " Select quantity,sellprice From inwardproduct"
            . " Where productid = '$ProductSplit[0]' and outward='0' ORDER BY createddate DESC limit 1 ;";

//    if (mysqli_multi_query($conn, $Select)) {
//        do {
//            /* store first result set */
//            if ($result = mysqli_store_result($conn)) {
//                $row = mysqli_fetch_array($result);
//
//                /* print your results */ 
//                    $qty = $row['quantity'];
//                    $SellPrice = $row['sellprice'];
//                    $Stock = $row['stock'];
//                    $productid = $row['productid'];
//                    $Margin = $row['margin'];
//                
//                mysqli_free_result($result);
//            }
//        } while (mysqli_next_result($conn));
//    }

    $result = mysqli_query($conn, $Select)or die(mysqli_error($conn));
    $rows = mysqli_fetch_array($result);
    $qty = $rows['quantity'];
    $SellPrice = $rows['sellprice'];

    $Select1 = " Select stock from inwardproduct Where productid = '$ProductSplit[0]' "
            . " ORDER BY createddate DESC limit 1;";
    $result1 = mysqli_query($conn, $Select1)or die(mysqli_error($conn));
    $rows1 = mysqli_fetch_array($result1);
    $Stock = $rows1['stock'];

    $Select2 = "Select productid,margin From productmaster Where productid = '$ProductSplit[0]'; ";
    $result2 = mysqli_query($conn, $Select2)or die(mysqli_error($conn));
    $rows2 = mysqli_fetch_array($result2);
    $productid = $rows2['productid'];
    $Margin = $rows2['margin'];

    $TotalStock = $Stock - $ConvertUnit;

    $SumPrice = (($ConvertUnit * $SellPrice) / $qty);
    $SumMargin = $SumPrice * $Margin / 100;
    $SellPrice1 = $SumPrice + $SumMargin;

    $Query_Distribution = "INSERT INTO distribution(distributionid,allocatedby, vendorid, productid, unit, quantity)"
            . "select UUID(), '$UserID','$Vendor', '$ProductSplit[0]', '$ProductSplit[1]', '$ConvertUnit';";
        $QueryDis_result = mysqli_multi_query($conn, $Query_Distribution)or die(mysqli_error($conn));
 
     // echo $Query_Distribution;
    $Query = "INSERT INTO inwardproduct(inwardproductid , userid, productid, quantity, "
            . "unit, buyprice, sellprice, inward, outward, stock)"
            . "select UUID(),'$UserID','$ProductSplit[0]','$ConvertUnit',"
            . "'$ProductSplit[1]','$SumPrice','$SellPrice1','0', '$ConvertUnit', '$TotalStock';";
//    echo $Query;
     $Query .= "INSERT INTO distributionstock(distributionstockid, vendorid, productid, unit, stock)"
            . "select UUID(),'$Vendor', '$ProductSplit[0]','$ConvertUnit','$ProductSplit[1]';";
     
//    echo $Query;
    $Query_result = mysqli_multi_query($conn, $Query)or die(mysqli_error($conn));
    echo "<script> alert ('Product is Successfully Distributed...!!!');window.location.replace('productdistribution.php');</script>";
    
  //  $URL = "productdistribution.php";
  //  echo $Dialog;
//    } else {
//        $InwardMsg = "jhsafdljsafdjsa";
//    }
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
                </div>
                <!-- Input -->
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2 id="Card_ProductDistribute">
                                    Product Distribution
                                </h2>
                            </div>
                            <div class="body">
                                <form id="d" class="form_validation" method="POST" enctype="multipart/form-data" 
                                      onsubmit="return ValDistribute(this);">
                                    <div class="row clearfix">
                                        <div class="col-sm-12">
                                            <div class="col-sm-6">    
                                                <input type="hidden" name="hdnDistributionID" id="hdnDistributionID"/>
                                                <select class="form-control show-tick" name="ddlVendor" id="ddlVendor">
                                                    <option value="-1">-- Select Vendor --</option>
                                                     <?php
                                                    $Select = mysqli_query($conn, "SELECT user.username,user.userid,
                                                            user.roleid,role.roleid,role.role
                                                           FROM userdetails user 
                                                           INNER JOIN rolemaster role ON role.role =  user.roleid
                                                            WHERE role.role='Vendor'");
                                                    while ($row = mysqli_fetch_array($Select)) {
                                                        ?>
                                                        <option value="<?php echo $row["username"]; ?>">
                                                            <?php echo $row["username"]; ?></option>
                                                            <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">                            
                                                <div class="form-group">
                                                    <select class="form-control show-tick" data-toggle="tooltip" title="" 
                                                            name="ddlProduct" id="ddlProduct" 
                                                            onchange="StockFun();">
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
                                                    <script>
                                                        function StockFun() {
                                                            $('#ddlProduct').on('change', function () {
                                                                var ProductID = this.value;
                                                                $.ajax({
                                                                    url: "getstock.php",
                                                                    type: "POST",
                                                                    data: {
                                                                        ProductID: ProductID
                                                                    },
                                                                    cache: false,
                                                                    success: function (data) {
                                                                        var msg = data.trim().split('~');
                                                                        if (msg[0] == "0")
                                                                        {
                                                                            $('#ddlProduct').attr('data-original-title', msg[1])
                                                                                    .tooltip('show');
                                                                            document.getElementById("btnSubmit").disabled = true;
                                                                        } else {
                                                                            $('#ddlProduct').attr('data-original-title', "")
                                                                                    .tooltip('hide');
                                                                            document.getElementById("btnSubmit").disabled = false;
                                                                        }
                                                                    }
                                                                });
                                                            });
                                                        }
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">                            
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="txtQuantity" id="txtQuantity" 
                                                               class="form-control"  placeholder="Quantity"/>
                                                    </div>
                                                    <span id="invalid1_text" class="text-danger" style="display:none;">* Quantity must be integer.</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <select class="form-control show-tick" name="ddlUnit" id="ddlUnit"
                                                            data-toggle="tooltip" title="" onchange="myFunction();">
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

                                        </div>
                                        <script>
                                            $("#txtQuantity").change(function () {
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
                                            });
//                                          
                                            function myFunction() {

                                                $('#ddlUnit').on('change', function () {
                                                    var ProductID = ddlProduct.value;
                                                    var Quantity = txtQuantity.value;
                                                    var Unit = this.value;
                                                    $.ajax({
                                                        url: "getproductquantity.php",
                                                        type: "POST",
                                                        data: {
                                                            "Quantity": Quantity,
                                                            "ProductID": ProductID,
                                                            "Unit": Unit
                                                        },
                                                        cache: false,
                                                        success: function (data) {
                                                            //   var msg = data.trim().split('~');
                                                            if (data != "")
                                                            {
                                                                $('#txtQuantity').attr('data-original-title', data)
                                                                        .tooltip('show');
                                                                document.getElementById("btnSubmit").disabled = true;
                                                            } else {
                                                                $('#txtQuantity').attr('data-original-title', "")
                                                                        .tooltip('hide');
                                                                document.getElementById("btnSubmit").disabled = false;
                                                            }
                                                        }
                                                    });
                                                });
                                            }
                                            function ValDistribute(frm) {

                                                if (frm.ddlVendor.value == "-1") {
                                                    var btn = $("button[data-id='ddlVendor']");
                                                    btn.addClass("invalid");
                                                    return false;
                                                } else {
                                                    var btn = $("button[data-id='ddlVendor']");
                                                    btn.removeClass("invalid");
                                                }

                                                if (frm.ddlProduct.value == "-1") {
                                                    var btn = $("button[data-id='ddlProduct']");
                                                    btn.addClass("invalid");
                                                    return false;
                                                } else {
                                                    var btn = $("button[data-id='ddlProduct']");
                                                    btn.removeClass("invalid");
                                                }

                                                var textVal1 = frm.txtQuantity.value;
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
                                            }

                                            // $(document).ready(function () {
                                            function Cancle() {
                                                //    $("input").click(function () {
                                                $("#d")[0].reset();
                                                document.getElementById("ddlVendor").selectedIndex = 0;
                                                var index = 0;
                                                var btn = $("button[data-id='ddlVendor']");
                                                var catname = $("#ddlVendor")[0].selectedOptions[0].text;
                                                var li = $("#ddlVendor").siblings("div").children().children("li[data-original-index='" +
                                                        index + "']");
                                                li.addClass("selected");
                                                li.siblings().removeClass();

                                                btn[0].setAttribute("title", catname);
                                                btn.children()[0].innerText = catname;

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

                                                document.getElementById("Card_ProductDistribute").innerHTML = "Product Distribution";
                                                document.getElementById("btnSubmit").value = "Add";
                                                //  });
                                            }
                                            // });
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
                                <h2>Product Distribution Details</h2>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                            <thead>
                                                <tr><th>#</th>
                                                    <th>Allocated By</th>
                                                    <th>Vendor</th>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if($_SESSION["Role"] == 'Admin'){
                                                 $Select_Product = "Select (select username from 
                                                userdetails where userid=dis.allocatedby) AS allocatedbyname, 
                                                (select username from userdetails 
                                                where username=dis.vendorid) AS vendorname,pro.productname, dis.unit, dis.quantity, 
                                                dis.createddate from distribution dis
                                                inner join  productmaster pro on pro.productname = dis.productid";
                                                $result_query = mysqli_query($conn, $Select_Product) or die(mysqli_error($conn));
                                                }else{
                                                $Select_Product = "Select (select username from 
                                                userdetails where userid=dis.allocatedby) AS allocatedbyname, 
                                                (select username from userdetails 
                                                where username=dis.vendorid) AS vendorname,pro.productname, dis.unit, dis.quantity, 
                                                dis.createddate from distribution dis
                                                inner join  productmaster pro on pro.productname = dis.productid where dis.vendorid='$UserID';";
                                                $result_query = mysqli_query($conn, $Select_Product) or die(mysqli_error($conn));
                                                }
                                                
                                                $count = 1;
                                                while ($rows = mysqli_fetch_array($result_query)) {
                                                    $allocatedbyname = $rows['allocatedbyname'];
                                                    $vendorname = $rows['vendorname'];
                                                    $ProductName = $rows['productname'];
                                                    $Quantity = $rows['quantity'];
                                                    $Unit = $rows['unit'];
                                                    $CreatedDate = $rows['createddate'];
                                                    $da = strtotime($CreatedDate);
                                                    $Date = date('Y/m/d', $da);

                                                    echo ' <tr>
                                                        <td>' . $count . '</td>
                                                        <td>' . $allocatedbyname . '</td>
                                                        <td>' . $vendorname . '</td>
                                                        <td>' . $ProductName . '</td>
                                                        <td>' . $Quantity . '</td>
                                                        <td>' . $Unit . '</td>
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