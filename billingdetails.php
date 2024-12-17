<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Calcutta');
include 'connection.php';
//include 'session.php';
$UserID = $_SESSION["UserID"];
//$URL = "";

$Date = date("Ymd");
$RandomNo = mt_rand(100000, 999999);
$BillNo = $Date . '-' . $RandomNo;
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <style>
            #btn_border #ddlDorpdown .btn{
                border: 1px solid;
            }
            .divheader{
                border-bottom: 1px solid rgba(204, 204, 204, 0.35);
                font-weight: normal;
                font-size: 18px;
                padding: 20px;
                margin-top: -27px;
                color: #111;
                margin-bottom: 20px;
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
                                <h2 id="Card_ProductSell">
                                    Product Selling
                                </h2>
                            </div>
                            <div class="body">
                                <form id="d" class="form_validation" method="POST" enctype="multipart/form-data" 
                                      onsubmit="return ValBill(this);">
                                    <div class="row clearfix">
                                        <div class="col-sm-12">  
                                            <div class="col-xs-6">
                                                <div class="input-daterange input-group">
                                                    <span class="input-group-addon">Bill No :</span>
                                                    <input type="hidden" name="hdnBillID" id="hdnBillID"/>
                                                    <input type="text" name="BillNo" 
                                                           style="background-color: #eee !important;" 
                                                           id="BillNo" value="<?php echo $BillNo; ?>"
                                                           class="form-control" readonly="" disabled/>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">                            
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="txtName" id="txtName"
                                                               class="form-control" placeholder="Seller Name"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">                            
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="date" name="txtDate" id="txtDate"
                                                               class="form-control"  value ="<?php echo date('Y-m-d'); ?>"
                                                               placeholder="Please choose a date..."/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>

                                <form id="d" class="form_validation" method="POST" enctype="multipart/form-data">
                                    <div class="col-sm-12"> 
                                        <h2 id="Card_AddProduct" class="divheader">
                                            Add Product 
                                        </h2>
                                        <div class="col-sm-6">                            
                                            <div class="form-group">
                                                <select class="form-control"  data-toggle="tooltip" title="" 
                                                        name="ddlProduct" id="ddlProduct" onchange="VendorStockFun();">
                                                    <option value="-1">-- Select Product --</option>
                                                    <?php
                                                    $Select = mysqli_query($conn, "SELECT productid,productname,isactive,unit"
                                                            . " FROM productmaster WHERE isactive='1'");
                                                    while ($row = mysqli_fetch_array($Select)) {
                                                        $ProUnit = $row["unit"];
                                                        ?>
                                                        <option value="<?php echo $row["productid"] . "~" . $row["productname"] . "~" . $ProUnit; ?>">
                                                            <?php echo $row["productname"]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <script>
                                                    function VendorStockFun() {
                                                        $('#ddlProduct').on('change', function () {
                                                            var ProductID = this.value;
                                                            $.ajax({
                                                                url: "getvendorstock.php",
                                                                type: "POST",
                                                                data: {
                                                                    ProductID: ProductID
                                                                },
                                                                cache: false,
                                                                success: function (data) {
//                                                                    var msg = data.trim().split('~');
                                                                    if (data != "")
                                                                    {
                                                                        $('#ddlProduct').attr('data-original-title', data)
                                                                                .tooltip('show');
                                                                        document.getElementById("btnMoreSubmit").disabled = true;
                                                                    } else {
                                                                        $('#ddlProduct').attr('data-original-title', "")
                                                                                .tooltip('hide');
                                                                        document.getElementById("btnMoreSubmit").disabled = false;
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
                                                           class="form-control"  placeholder="Quantity" value=""/>
                                                </div>
                                                <span id="invalid1_text" class="text-danger" style="display:none;">* Quantity must be integer.</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select class="form-control" name="ddlUnit" id="ddlUnit"  
                                                        data-toggle="tooltip" title="" onchange="myFunction();">
                                                    <option value="-1">-- Select Unit --</option>
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
                                                    <input type="text" name="txtPrice" id="txtPrice"
                                                           class="form-control"  placeholder="Price" value=""/>
                                                </div>
                                                <span id="invalid_text" class="text-danger" style="display:none;">* Price must be integer.</span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-sm-3">
                                                    <input class="btn btn-block bg-green waves-effect" 
                                                           id="btnMoreSubmit" name="btnMoreSubmit" type="button"
                                                           value="Add More"  onclick="add_element_to_array()"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="divsucc"></div>
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
                                                    url: "getvendorproduct.php",
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
                                                            document.getElementById("btnMoreSubmit").disabled = true;
                                                        } else {
                                                            $('#txtQuantity').attr('data-original-title', "")
                                                                    .tooltip('hide');
                                                            document.getElementById("btnMoreSubmit").disabled = false;
                                                        }
                                                    }
                                                });
                                            });
                                        }

                                        var w = 0;
                                        var x = 1;
                                        var y = 2;
                                        var z = 3;

                                        var arr = [];

                                        function add_element_to_array()
                                        {
                                            if (ddlProduct.value == "-1") {
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
                                                txtQuantity.classList.add('invalid');
                                                document.getElementById("invalid1_text").style.display = 'block';
                                                return false;
                                            } else {
                                                txtQuantity.classList.remove('invalid');
                                                document.getElementById("invalid1_text").style.display = 'none';
                                            }

                                            if (ddlUnit.value == "-1") {
                                                var btn = $("button[data-id='ddlUnit']");
                                                btn.addClass("invalid");
                                                return false;
                                            } else {
                                                var btn = $("button[data-id='ddlUnit']");
                                                btn.removeClass("invalid");
                                            }

                                            var textVal = txtPrice.value;
                                            var regex = /^\d+(?:[.,]\d+)*$/;
                                            var passed = textVal.match(regex);
                                            if (passed == null) {
//                                                    if (frm.txtBuyPrice.value == "") {
                                                txtPrice.classList.add('invalid');
                                                document.getElementById("invalid_text").style.display = 'block';
                                                return false;
                                            } else {
                                                txtPrice.classList.remove('invalid');
                                                document.getElementById("invalid_text").style.display = 'none';
                                            }

                                            var prd = document.getElementById("ddlProduct").value.split("~");
                                            var json = {
                                                "ProductId": prd[0],
                                                "ProductName": prd[1],
                                                "ProductUnit": prd[2],
                                                "Quantity": document.getElementById("txtQuantity").value,
                                                "Unit": document.getElementById("ddlUnit").value,
                                                "Price": document.getElementById("txtPrice").value
                                            };
                                            arr.push(json);
//                                            alert("Element: " + array[x] + " Added at index " + x);
//                                            w++;
//                                            x++;
//                                            y++;
//                                            z++;

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

                                            document.getElementById("txtQuantity").value = "";
                                            document.getElementById("ddlUnit").selectedIndex = 0;
                                            var index = 0;
                                            var btn = $("button[data-id='ddlUnit']");
                                            var catname = $("#ddlUnit")[0].selectedOptions[0].text;
                                            var li = $("#ddlUnit").siblings("div").children().children("li[data-original-index='" +
                                                    index + "']");
                                            li.addClass("selected");
                                            li.siblings().removeClass();

                                            btn[0].setAttribute("title", catname);
                                            btn.children()[0].innerText = catname;
                                            document.getElementById("txtPrice").value = "";
//                                        }
//
//                                        function display_array()
//                                        {
                                            var e = TempTable(arr);
                                            document.getElementById("Result").innerHTML = e;
                                        }

                                        function EditBillProduct(id, qty, unit, price) {
                                            document.getElementById("ddlProduct").value = id;
                                            document.getElementById("ddlUnit").value = unit;
                                            document.getElementById("txtQuantity").value = qty;
                                            document.getElementById("txtPrice").value = price;

                                            var index = document.getElementById("ddlProduct").selectedIndex;
                                            var btn = $("button[data-id='ddlProduct']");
                                            var catname = $("#ddlProduct")[0].selectedOptions[0].text;
                                            var li = $("#ddlProduct").siblings("div").children().children("li[data-original-index='" +
                                                    index + "']");
                                            li.addClass("selected");
                                            li.siblings().removeClass();

                                            btn[0].setAttribute("title", catname);
                                            btn.children()[0].innerText = catname;

                                            index = document.getElementById("ddlUnit").selectedIndex;
                                            btn = $("button[data-id='ddlUnit']");
                                            catname = $("#ddlUnit")[0].selectedOptions[0].text;
                                            li = $("#ddlUnit").siblings("div").children().children("li[data-original-index='" +
                                                    index + "']");
                                            li.addClass("selected");
                                            li.siblings().removeClass();

                                            btn[0].setAttribute("title", catname);
                                            btn.children()[0].innerText = catname;

                                            DeleteBillProduct(id);
                                        }

                                        function DeleteBillProduct(id) {
                                            //delete arr[id.split("~")[0]];

                                            jQuery.each(arr, function (i, val) {
                                                if (val.ProductId == id.split("~")[0]) // delete index
                                                {
                                                    arr.splice(i, 1);
                                                    return false;
                                                }
                                            });
                                            var e = TempTable(arr);

                                            document.getElementById("Result").innerHTML = e;
                                        }

                                        function TempTable(arr) {
                                            var e = "";
                                            for (var i = 0; i < arr.length; i++)
                                            {
                                                e += "<tr>"
                                                        + "<td>" + (i + 1) + "</td>"
                                                        + "<td>" + arr[i]["ProductName"] + "</td>"
                                                        + "<td>" + arr[i]["Quantity"] + "</td>"
                                                        + "<td>" + arr[i]["Unit"] + "</td>"
                                                        + "<td>" + arr[i]["Price"] + "</td>"
                                                        + "<td>"
                                                        + "<a href='#' style='margin-right: 15px;' class='btn bg-light-blue btn-circle waves-effect "
                                                        + "waves-circle waves-float'"
                                                        + " onclick='EditBillProduct(\"" + arr[i]["ProductId"] + "~" + arr[i]["ProductName"] + "~" + arr[i]["ProductUnit"] + "\",\"" +
                                                        arr[i]["Quantity"] + "\",\"" +
                                                        arr[i]["Unit"] + "\",\"" +
                                                        arr[i]["Price"] + "\")'>"
                                                        + "<i class='material-icons'>mode_edit</i></a>"
                                                        + "<a class='btn bg-red btn-circle waves-effect "
                                                        + "waves-circle waves-float'"
                                                        + "onclick='DeleteBillProduct(\"" + arr[i]["ProductId"] + "\");'>"
                                                        + "<i class='material-icons'>delete</i></a>"
                                                        + "</td>"
                                                        + "</tr>";
                                            }
                                            return e;
                                        }

                                        function ValBill() {

                                            if (txtName.value == "") {
                                                txtName.classList.add('invalid');
                                                return false;
                                            } else {
                                                txtName.classList.remove('invalid');
                                            }

                                            var dateString = document.getElementById('txtDate').value;
                                            var myDate = new Date(dateString);
                                            var today = new Date();
                                            if (myDate >= today || dateString == "") {
                                                txtDate.classList.add('invalid');
                                                $('#txtDate').attr('data-original-title', "Invalid date.")
                                                        .tooltip('show');
                                                return false;
                                            } else {
                                                txtDate.classList.remove('invalid');
                                            }

                                            if (arr.length == 0) {
                                                $('#ddlProduct').attr('data-original-title', "Insert Product Details.")
                                                        .tooltip('show');
                                                return false;
                                            }
                                            else {
                                                $('#ddlProduct').attr('data-original-title', "")
                                                        .tooltip('hide');
                                            }

                                            $.ajax({
                                                method: "post",
                                                url: "InsertBillingDetails.php",
                                                data: {
                                                    'Products': JSON.stringify(arr),
                                                    'Date': txtDate.value,
                                                    'BillNo': BillNo.value,
                                                    'Name': txtName.value
                                                },
                                                success: function (res) {
                                                    document.getElementById("divsucc").innerHTML = res;
                                                    //alert(res);
                                                },
                                                error: function (res) {
                                                    alert(res);
                                                }
                                            });

                                        }
                                        // $(document).ready(function () {
                                        function Cancle() {
                                            //    $("input").click(function () {
                                            $("#d")[0].reset();
                                            document.getElementById("Card_ProductSell").innerHTML = "Product Selling";
                                            document.getElementById("btnSubmit").value = "Add";
                                            //  });
                                        }
                                        //                                            });
                                        $(document).ready(function () {
                                            document.getElementById("Result").innerHTML = "<tr class='odd' style='background-color: #f9f9f9;'><td valign='top' colspan='6' class='dataTables_empty'>No Product added yet</td></tr>";
                                        });
                                    </script>
                                    <div class="body table-responsive" > 
                                        <table class="table table-hover table-bordered"> 
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Sell Price</th>
                                                    <th>Action</th>
                                                </tr></thead>
                                            <tbody  id="Result">
                                            </tbody>
                                        </table>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-sm-3">
                                                    <input class="btn btn-block bg-green waves-effect" 
                                                           id="btnInsert" name="btnMoreSubmit" type="button"
                                                           value="Insert"  onclick="ValBill()
                                                                           ;"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix" id="btn_border">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>Billing Details</h2>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                            <thead>
                                                <tr><th>#</th>
                                                    <th>Bill No</th>
                                                    <th>Buyer Name</th>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Price</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if($_SESSION["Role"] == 'Admin'){
                                                $Select_Product = "select bill.billno, bill.name, bill.date, subbill.price, 
                                                subbill.unit, subbill.quantity, pro.productname 
                                                FROM billingmaster bill inner join billingsubmaster subbill ON subbill.billid = bill.billid 
                                                inner join productmaster pro ON pro.productid = subbill.productid
                                                 order by bill.billno ;";
                                                $result_query = mysqli_query($conn, $Select_Product) or die(mysqli_error($conn));
                                                }else{
                                                $Select_Product = "select bill.billno, bill.name, bill.date, subbill.price, 
                                                subbill.unit, subbill.quantity, pro.productname 
                                                FROM billingmaster bill inner join billingsubmaster subbill ON subbill.billid = bill.billid 
                                                inner join productmaster pro ON pro.productid = subbill.productid
                                                 where bill.vendorid='$UserID' order by bill.billno ;";
                                                $result_query = mysqli_query($conn, $Select_Product) or die(mysqli_error($conn));
                                                }
                                                 $count = 1;
                                                while ($rows = mysqli_fetch_array($result_query)) {
                                                    $billno = $rows['billno'];
                                                    $name = $rows['name'];
                                                    $date = $rows['date'];
                                                    $price = $rows['price'];
                                                    $unit = $rows['unit'];
                                                    $quantity = $rows['quantity'];
                                                    $productname = $rows['productname'];

                                                    echo ' <tr>
                                                        <td>' . $count . '</td>
                                                        <td>' . $billno . '</td>
                                                        <td>' . $name . '</td>
                                                        <td>' . $productname . '</td>
                                                        <td>' . $quantity . '</td>
                                                        <td>' . $unit . '</td>
                                                        <td>' . $price . '</td>
                                                        <td>' . $date . '</td></tr>';
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