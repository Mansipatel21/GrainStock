<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Calcutta');
include 'connection.php';
//include 'session.php';
$UserID = $_SESSION["UserID"];
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
                <div class="row clearfix" id="trying">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <input type="hidden" id="hdnReport" value="Inward Report" />
                                <h2>Inward Report</h2>
                                <div class="body">
                                    <form method="POST" enctype="multipart/form-data" onsubmit="return ValDate(this);">
                                        <div class="table-responnsive">
                                            <div class="row">
                                                <div class="col-xs-5">
                                                    <div class="input-daterange input-group">
                                                        <span class="input-group-addon">From</span>
                                                        <div class="form-line">
                                                            <input type="date" name="txtFromDate" id="txtFromDate"
                                                                   class="form-control" value="<?php echo Date('Y-m-d', strtotime('-6 days'));?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-5">
                                                    <div class="input-daterange input-group">
                                                        <span class="input-group-addon">To</span>
                                                        <div class="form-line">
                                                            <input type="date" name="txtToDate" id="txtToDate"
                                                                   class="form-control" value="<?php echo Date('Y-m-d');?>"/>

                                                        </div>
                                                        <span id="invalid_text" class="text-danger" style="display:none;">* From date is greater than To date.</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input class="btn btn-block bg-green waves-effect" 
                                                           id="btnSubmit" name="btnSubmit" type="submit" value="Search"/>
                                                </div>
                                            </div>

                                            <script>
                                                function ValDate() {
                                                    var FromDate = document.getElementById('txtFromDate').value;
                                                    var ToDate = document.getElementById('txtToDate').value;
                                                    var myDateFrom = new Date(FromDate);
                                                    var myDateTo = new Date(ToDate);
                                                    var today = new Date();
                                                    if (FromDate != "") {
                                                        if (myDateFrom > today) {
                                                            txtFromDate.classList.add('invalid');
                                                            return false;
                                                        } else {
                                                            txtFromDate.classList.remove('invalid');
                                                        }
                                                    }
                                                    if (ToDate != "") {
                                                        if (myDateTo > today) {
                                                            txtToDate.classList.add('invalid');
                                                            return false;
                                                        } else {
                                                            txtToDate.classList.remove('invalid');
                                                        }
                                                    }
                                                    if ((FromDate != "") && (ToDate != "")) {
                                                        if (ToDate < FromDate) {
                                                            txtToDate.classList.add('invalid');
                                                            document.getElementById("invalid_text").style.display = 'block';
                                                            return false;
                                                        } else {
                                                            txtToDate.classList.remove('invalid');
                                                            document.getElementById("invalid_text").style.display = 'none';
                                                        }
                                                    }
                                                }
                                            </script>

                                            <table class="table table-bordered table-striped table-hover js-exportable dataTable">
                                                <thead>
                                                    <tr><th>#</th>
                                                        <th>Product</th>
                                                        <th>Quantity</th>
                                                        <th>Unit</th>
                                                        <th>Price</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (isset($_POST['btnSubmit'])) {
                                                        $FromDate = $_POST['txtFromDate'];
                                                        $ToDate = $_POST['txtToDate'];
                                                        if ($ToDate == "") {
                                                            $ToDate = date("y-m-d");
                                                        }
                                                        if ($FromDate == "") {
                                                            $FromDate = date("Y")+"-01-01";
                                                        }
                                                        $FromDate = $FromDate . " 00:00:00";
                                                        $ToDate = $ToDate . " 23:59:00";
                                                        $Select_Product = "select buy.quantity, buy.unit, buy.buyprice, buy.createddate, pro.productname from inwardproduct buy inner join 
                                                        productmaster pro on pro.productname = buy.productid where buy.inward != 0 and   
                                                        buy.createddate in (select createddate from inwardproduct where 
                                                        createddate>= '$FromDate' and createddate<= '$ToDate') order by buy.createddate desc;";
                                                        $result = mysqli_query($conn, $Select_Product) or die(mysqli_error($conn));
//                                                    var_dump($result);
                                                    } else {
                                                        $DateTrim = date('Y-m-d');
                                                        $Select_Product = "select buy.quantity, buy.unit, 
                                                    buy.buyprice, buy.createddate, pro.productname from inwardproduct buy
                                                    inner join productmaster pro on pro.productname = buy.productid 
                                                    where buy.inward != 0 and CAST(buy.createdDate AS DATE)='$DateTrim'
                                                    order by buy.createddate desc ;";
//                                                        echo $Select_Product;
                                                        $result = mysqli_query($conn, $Select_Product) or die(mysqli_error($conn));
                                                    }
                                                    //echo $Select_Product;
                                                    $count = 1;
                                                    while ($rows = mysqli_fetch_array($result)) {
                                                        $ProductName = $rows['productname'];
                                                        $Quantity = $rows['quantity'];
                                                        $Unit = $rows['unit'];
                                                        $Price = $rows['buyprice'];
                                                        $CreatedDate = $rows['createddate'];
                                                        $da = strtotime($CreatedDate);
                                                        $Date = date('Y/m/d', $da);

                                                        echo ' <tr>
                                                        <td>' . $count . '</td>
                                                        <td>' . $ProductName . '</td>
                                                        <td>' . $Quantity . '</td>
                                                        <td>' . $Unit . '</td>
                                                        <td>' . $Price . '</td>
                                                        <td>' . $Date . '</td></tr>';
                                                        $count++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </body>

</html>