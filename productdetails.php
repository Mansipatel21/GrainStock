<?php
require 'connection.php';

if (isset($_POST['btnSubmit'])) {
    $ProductID = $_POST['hdnProductID'];
    $Category = $_POST['ddlCategory'];
    $ProductName = $_POST['txtProductName'];
    $Unit = $_POST['ddlUnit'];
    $Margin = $_POST['txtMargin'];
    $HdnImgPath = $_POST['hdnImgPath'];
    $hdnImgBase = $_POST['hdnImgBase'];

     if(!empty($_FILES["file"]["name"])){
          $basedir = __DIR__;
        $basedir = str_replace("\\", "/", $basedir);
        $target_dir = $basedir . "/uploads/";
        __DIR__ . DIRECTORY_SEPARATOR . "<br/>" . $target_dir;
        if (!file_exists($target_dir))
            mkdir($target_dir);
        $target_dir.="ProductImg/Img/";
                
     //$targetDir = "uploads/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $target_dir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
        
        if ($fileType == "jpg" || $fileType == "png" || $fileType == "jpeg" || $fileType == "gif" || $fileType == "pdf") {
            if (move_uploaded_file($_FILES['file']["tmp_name"], $targetFilePath)) {
                
                if ($HdnImgPath != "" || $HdnImgPath != NULL) {
                    $OldImgPath = $HdnImgPath;
                    if ($OldImgPath != "") {
                        if (file_exists($OldImgPath)) {
                            unlink($OldImgPath);
                        }
                    }
                }
                $path_img = substr($targetFilePath, strpos($targetFilePath, "uploads"), strlen($targetFilePath));
                $HdnImgPath = $targetFilePath;
            }
        }
    }

    if ($ProductID == "") {
        $Product_Insert_qry = "INSERT INTO productmaster (productid, userid, categoryid, productname, imagepath, unit, margin, isactive) "
                . "VALUES (UUID(),'jhjh', '$Category', '$ProductName', "
                . "'$targetFilePath', '$Unit','$Margin','1')";
        $Query_Result = mysqli_query($conn, $Product_Insert_qry) or die(mysqli_error($conn));
        echo"<script> alert ('Product is successfully Inserted !');window.location.replace('productdetails.php');</script>";
    } else {
        $Query_Product = "UPDATE productmaster SET categoryid='$Category',productname='$ProductName',"
                . "imagepath='$HdnImgPath', "
                . "unit='$Unit',margin='$Margin', updateddate=current_timestamp() WHERE productid='$ProductID'";
//                 echo $Query_Product;
        $Query_Result = mysqli_query($conn, $Query_Product)or die(mysqli_error($conn));
        echo "<script> alert ('Product is successfully Updated !');window.location.replace('productdetails.php');</script>";
//        echo $Query_Product;
    }
// Display status message
//echo $statusMsg;
}
?>
<?php
include 'header.php';
?>
<body class="theme-green">
    <section class="content">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2 id="Card_Product">
                            Add Product Category
                        </h2>
                    </div>
                    <div class="body">

                        <form id="d" class="form_validation" method="POST" 
                              enctype="multipart/form-data" onsubmit="return ValProduct(this);" >
                            <input type="hidden" name="hdnImgBase" id="hdnImgBase" value=""/>
                            <input type="hidden" name="hdnImgPath" id="hdnImgPath" value=""/>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="col-sm-6" id="div1">                            
                                        <select class="form-control show-tick" name="ddlCategory" id="ddlCategory">
                                            <option value="-1" selected="selected">-- Select Product Category --</option>
                                            <?php
                                                    $Select_Category = mysqli_query($conn, "SELECT categoryid, catid, categoryname, description, isactive "
                                                            . "FROM categorymaster WHERE isactive='1'");
                                                    while ($row = mysqli_fetch_array($Select_Category)) {
                                                        ?>
                                                        <option value="<?php echo $row["categoryname"]; ?>">
                                                            <?php echo $row["categoryname"]; ?></option>
                                                        <?php
                                                    }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">                            
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="hidden" name="hdnProductID" id="hdnProductID"/>
                                                <input type="text" name="txtProductName" id="txtProductName"
                                                       class="form-control" placeholder="Product Name"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" id="divUnit">                            
                                        <select class="form-control show-tick" name="ddlUnit" id="ddlUnit">
                                            <option value="-1" selected="selected">-- Select Unit --</option>
                                            <?php
                                            $Select_Unit = mysqli_query($conn, "SELECT unitid, unit, description "
                                                    . "FROM unitmaster");
                                            while ($rows = mysqli_fetch_array($Select_Unit)) {
                                                ?>
                                                <option value="<?php echo $rows["unit"]; ?>">
                                                    <?php echo $rows["unit"]; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">                            
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="txtMargin" id="txtMargin"
                                                       class="form-control" placeholder="Margin (Percentage%)"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12"> 
                                        <h5>Product Image</h5>
                                        <div class="form-group">
                                            <div id="preview_img"></div> 
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0px">
                                            <div id="Uploaded_img"></div>
                                        </div>
                                        <div id="frmFileUpload" class="dropzone">
                                            <div class="dz-message">
                                                <div class="drag-icon-cph">
                                                    <i class="material-icons">touch_app</i>
                                                </div>
                                                <h3>Drop files here or click to upload.</h3>
                                                <em>(This is a dropzone. Selected files are uploaded.)</em>
                                            </div>
                                            <div class="fallback">
                                                <input name="file" type="file" id="file" />
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                                <script>
                                    function previewImages() {

                                        var $preview = $('#preview_img').empty();
                                        if (this.files)
                                            $.each(this.files, readAndPreview);

                                        function readAndPreview(i, file) {

                                            if (!/\.(jpe?g|png|gif)$/i.test(file.name)) {
                                                return alert(file.name + " is not an image");
                                            } // else...

                                            var reader = new FileReader();

                                            $(reader).on("load", function () {
                                                $preview.append($("<img/>", {src: this.result, height: 160, width: 180}));
                                            });
                                            reader.readAsDataURL(file);
                                        }
                                    }

                                    $('#file').on("change", previewImages);

                                    function ValProduct(frm) {

                                        if (frm.ddlCategory.value == "-1") {
                                            var btn = $("button[data-id='ddlCategory']");
                                            btn.addClass("invalid");
//                                                    frm.ddlCategory.classList.add('invalid');
                                            return false;
                                        } else {
                                            var btn = $("button[data-id='ddlCategory']");
                                            btn.removeClass("invalid");
//                                                    frm.ddlCategory.classList.remove('invalid');
                                        }

                                        if (frm.txtProductName.value == "") {
                                            frm.txtProductName.classList.add('invalid');
                                            return false;
                                        } else {
                                            frm.txtProductName.classList.remove('invalid');
                                        }

                                        if (frm.ddlUnit.value == "-1") {
                                            var btn = $("button[data-id='ddlUnit']");
                                            btn.addClass("invalid");
//                                                    frm.ddlUnit.classList.add('invalid');
                                            return false;
                                        } else {
                                            var btn = $("button[data-id='ddlUnit']");
                                            btn.removeClass("invalid");
//                                                    frm.ddlUnit.classList.remove('invalid');
                                        }

                                        if (frm.txtMargin.value == "") {
                                            frm.txtMargin.classList.add('invalid');
                                            return false;
                                        } else {
                                            frm.txtMargin.classList.remove('invalid');
                                        }
                                    }

                                    // $(document).ready(function () {
                                    function Cancle() {
                                        //    $("input").click(function () {
                                        $("#d")[0].reset();

                                        document.getElementById("ddlCategory").selectedIndex = 0;
                                        document.getElementById("txtProductName").value = "";
                                        document.getElementById("ddlUnit").selectedIndex = 0;
                                        document.getElementById("txtMargin").value = "";
                                        document.getElementById("Uploaded_img").innerHTML = "";
                                        document.getElementById("Card_Product").innerHTML = "Add Product";
                                        document.getElementById("btnSubmit").value = "Add";
                                        //  });
                                        var index = 0;
                                        var btn = $("button[data-id='ddlCategory']");
                                        var catname = $("#ddlCategory")[0].selectedOptions[0].text;
                                        var li = $("#ddlCategory").siblings("div").children().children("li[data-original-index='" +
                                                index + "']");
                                        li.addClass("selected");
                                        li.siblings().removeClass();

                                        btn[0].setAttribute("title", catname);
                                        btn.children()[0].innerText = catname;

                                        var index = document.getElementById("ddlUnit").selectedIndex;
                                        var btn = $("button[data-id='ddlUnit']");
                                        var catname = $("#ddlUnit")[0].selectedOptions[0].text;
                                        var li = $("#ddlUnit").siblings("div").children().children("li[data-original-index='" +
                                                index + "']");
                                        li.addClass("selected");
                                        li.siblings().removeClass();

                                        btn[0].setAttribute("title", catname);
                                        btn.children()[0].innerText = catname;
                                        
                                         document.getElementById("Card_Product").innerHTML = "Add Product";
                                        document.getElementById("btnSubmit").value = "Add";
                                    }
                                </script>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <input class="btn btn-block bg-green waves-effect" data-type="success" 
                                                   id="btnSubmit" name="btnSubmit" type="submit" value="Add"/>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="button" class="btn btn-block bg-green waves-effect"
                                                   value="Cancel" onclick="Cancle();"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>  
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Product Details</h2>
                        <span id="message"></span>
                        <div class="body table-responsive" id="user_data">                                
                            <?php
                            $Select_Product = "SELECT  pro.userid, pro.productid, pro.categoryid, pro.productname,"
                                    . " uni.unit,uni.unitid,pro.imagepath, pro.margin, pro.isactive, pro.updateddate,"
                                    . " cat.categoryname FROM productmaster pro"
                                    . " inner join unitmaster uni ON  uni.unit = pro.unit"
                                    . " inner join categorymaster cat ON  cat.categoryname = pro.categoryid"
                                    . " WHERE pro.isactive = '1';";
//                                echo $Select_Product; //die();
                            $result_query = mysqli_query($conn, $Select_Product) or die(mysqli_error($conn));
//                    var_dump($result_query);
                            if (mysqli_num_rows($result_query) != 0) {
                                $count = 1;
                                echo'<table class="table table-hover"> 
                                <thead>
                                    <tr>
                                     <th>#</th>
                                        <th>Product Image</th>
                                         <th>Category</th>
                                        <th>Product Name</th>
                                        <th>Unit</th>
                                        <th>Margin</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>   
                                     <tbody>';
                                while ($rows = mysqli_fetch_array($result_query)) {
//                                            var_dump($rows);
//                                            echo $rows['unit'];
                                    $ProductID = $rows['productid'];
                                    $Category = $rows['categoryname'];
                                    $CategoryID = $rows['categoryid'];
                                    $ProductImg = $rows['imagepath'];
                                    $ProductName = $rows['productname'];
                                    $DBUnit = $rows['unit'];
                                    $margin = $rows['margin'];

                                    $img_profile = substr($ProductImg, strpos($ProductImg, "uploads"), strlen($ProductImg));
                                    if (!file_exists($img_profile)) {
                                        $img_profile = "";
                                    }
//                                        echo'<a class="popup-a" href="' . $Event_Img . '"><img src="' . $Event_Img . '" style="width: 20%;height: 100px;" alt=""></a>';
                                    echo ' <tr class="row-item">
                                      <td>' . $count . '</td>
                                           <td><img src="' . $img_profile . '" alt="" width="70" height="70" title="admin" id="user-profile" class="img-circle"></td> '
                                    . '<td>' . $CategoryID . '</td> '
                                    . '<td>' . $ProductName . '</td>
                                            <td>' . $DBUnit . '</td><td>' . $margin . ' %</td>';
                                    echo ' <td><a class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float"
                                       onclick="EditProduct(\'' . $ProductID . '\',\'' . $img_profile . '\','
                                    . '\'' . $CategoryID . '\',\'' . $ProductName . '\',\'' . $DBUnit . '\',
                                            \'' . $margin . '\')">
                                            <i class="material-icons">mode_edit</i></a></td>                         						
                                          <td><a class="btn bg-red btn-circle waves-effect waves-circle waves-float" 
                                          onclick="ShowDelete(\'' . $rows['productid'] . '\',);"><i class="material-icons">delete</i></a><td>
                                    </tr>';
                                    $count++;
                                }
                                echo "<input type='hidden' id='counts' name='counts' value='$count'/> ";
                                echo'  </tbody>
                            </table>';
                            } else {
                                echo '<div class="alert alert-info"><strong><large>No record found!!.</large></strong></div>';
                            }
                            ?>
                            <ul class="pagination pagination-sm custom-pagination" id="ulPage"></ul>
                            <!-- prepare necessary element attributes to pass parameters for pagination -->
                            <input type="hidden" id="itemCount" value="" />
                            <input type="hidden" id="pageLimit" value="5" />
                            <script>

                                function EditProduct(ProductID, ProductImg, Category, ProductName, Unit, Margin) {

                                    document.getElementById("hdnProductID").value = ProductID;
                                    document.getElementById("Uploaded_img").innerHTML = "";
                                    document.getElementById("hdnImgPath").value = ProductImg;
                                    if (ProductImg != "") {
                                        var values = ProductImg.split(',');

                                        for (var i = 0; i < values.length; i++) {

                                            var str = values[i];
                                            var mySubString = str.substring(str.lastIndexOf("uploads"));

                                            var img = document.createElement('img');
                                            img.src = mySubString;
                                            img.id = "new";
                                            img.height = 160;
                                            img.width = 180;
                                            img.style.margin = "5px 5px 5px 0px";

                                            var src = document.getElementById("Uploaded_img");
                                            src.appendChild(img);
                                        }
                                    }
                                    document.getElementById("ddlCategory").value = Category;
                                    var index = document.getElementById("ddlCategory").selectedIndex;
                                    var btn = $("button[data-id='ddlCategory']");
                                    var catname = $("#ddlCategory")[0].selectedOptions[0].text;
                                    var li = $("#ddlCategory").siblings("div").children().children("li[data-original-index='" +
                                            index + "']");
                                    li.addClass("selected");
                                    li.siblings().removeClass();
                                    btn[0].setAttribute("title", catname);
                                    btn.children()[0].innerText = catname;

                                    document.getElementById("txtProductName").value = ProductName;

                                    document.getElementById("ddlUnit").value = Unit;
                                    var index1 = document.getElementById("ddlUnit").selectedIndex;
                                    var btnunit = $("button[data-id='ddlUnit']");
                                    var unitname = $("#ddlUnit")[0].selectedOptions[0].text;
                                    var li = $("#ddlUnit").siblings("div").children().children("li[data-original-index='" +
                                            index1 + "']");
                                    li.addClass("selected");
                                    li.siblings().removeClass();

                                    btnunit[0].setAttribute("title", unitname);
                                    btnunit.children()[0].innerText = unitname;
                                    document.getElementById("txtMargin").value = Margin;

                                    if (ProductID != "") {
                                        document.getElementById("Card_Product").innerHTML = "Edit Product";
                                        document.getElementById("btnSubmit").value = "Update";
                                    } else {
                                        document.getElementById("Card_Product").innerHTML = "Add Product";
                                        document.getElementById("btnSubmit").value = "Add";
                                    }
                                }

                                function ShowDelete(ProductID) {
                                    var text = "Are you Sure! You want to delete this Record!.";
                                    if (confirm(text) == true) {
                                        var Data = {type: "Product_Delete", ProductID: ProductID}
//                                              text = "You pressed OK!";
                                        $.ajax({
                                            method: "POST",
                                            url: "delete.php",
                                            data: Data,
                                            success: function (data) {
                                                if (data = "1") {
                                                    alert("Product Deleted Successfully!");
                                                    window.location.replace("productdetails.php");
                                                }
                                            }
                                        });
                                    }

                                }
                                $(document).ready(function () {
                                    setTimeout(function () {
                                        $('#ulPage').rpmPagination({
                                            limit: parseInt($('#pageLimit').val()),
                                            total: parseInt($('#itemCount').val()),
                                            domElement: '.row-item'
                                        });
                                    }, 100);
                                });
                                function Cancle() {
                                                //    $("input").click(function () {
                                                $("#d")[0].reset();

                                                document.getElementById("ddlCategory").selectedIndex = 0;
                                                document.getElementById("txtProductName").value = "";
                                                document.getElementById("ddlUnit").selectedIndex = 0;
                                                document.getElementById("txtMargin").value = "";
                                                document.getElementById("Uploaded_img").innerHTML = "";
                                                document.getElementById("Card_Product").innerHTML = "Add Product";
                                                document.getElementById("btnSubmit").value = "Add";
                                                //  });
                                                var index = 0;
                                                var btn = $("button[data-id='ddlCategory']");
                                                var catname = $("#ddlCategory")[0].selectedOptions[0].text;
                                                var li = $("#ddlCategory").siblings("div").children().children("li[data-original-index='" +
                                                        index + "']");
                                                li.addClass("selected");
                                                li.siblings().removeClass();

                                                btn[0].setAttribute("title", catname);
                                                btn.children()[0].innerText = catname;

                                                var index = document.getElementById("ddlUnit").selectedIndex;
                                                var btn = $("button[data-id='ddlUnit']");
                                                var catname = $("#ddlUnit")[0].selectedOptions[0].text;
                                                var li = $("#ddlUnit").siblings("div").children().children("li[data-original-index='" +
                                                        index + "']");
                                                li.addClass("selected");
                                                li.siblings().removeClass();

                                                btn[0].setAttribute("title", catname);
                                                btn.children()[0].innerText = catname;
                                            }
                            </script>
                        </div>
                    </div> 
                </div>
            </div>
        </div>

    </section>