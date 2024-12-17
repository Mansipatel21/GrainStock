<?php
   require 'connection.php';
 
 if(isset($_POST['btnCategorySubmit'])){
     $CategoryID = $_POST['hdnCategoryID'];
     $categoryname = $_POST['txt_CategoryName'];
     $description = $_POST['txt_Description'];
     
     $CatID =rand(100000,999999);
     if($CategoryID == ""){
     $Category_Insert_qry="INSERT INTO categorymaster(categoryid, catid, categoryname, description, isactive)"
             . " VALUES (UUID(), '$CatID','$categoryname', '$description', '1');";
     
     $Query_Result =mysqli_query($conn, $Category_Insert_qry) or die (mysqli_error($conn));
     echo "<script> alert ('Category is successfully Inserted !');window.location.replace('category.php');</script>";
     }else{
         $Query = "UPDATE categorymaster SET categoryname='$categoryname',description='$description', "
                . "updateddate=current_timestamp() WHERE categoryid='$CategoryID'";
//    echo $Query;
        $Query_result = mysqli_query($conn, $Query)or die(mysqli_error($conn));
       echo "<script> alert ('Category is successfully Updated !');window.location.replace('category.php');</script>";
   }
 }
?>
<!DOCTYPE html>

<?php

include 'header.php';

?>


<body class="theme-Green">
    

    <section class="content">
        <div class="container-fluid">
           
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2 id="Card_Category">
                                Add Product Category   
                            </h2>  
                        </div>
                        <div class="body">
                         <form id="d" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="hdnCategoryID" name="hdnCategoryID"/>
                                  <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line" id="editcat">
                                            <input type="text" name="txt_CategoryName" id="txt_CategoryName" class="form-control">
                                            <label class="form-label">Enter Product Category</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line" id="editdes">
                                            <textarea type="text" name="txt_Description" id="txt_Description" class="form-control"></textarea>
                                            <label class="form-label">Enter Description of Category</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                        <input class="btn btn-block bg-green waves-effect" id="btnCategorySubmit" name="btnCategorySubmit" type="submit" value="Add">
                                 </div>
                                    <div class="col-sm-2">
                                        <input type="button" class="btn btn-block bg-green waves-effect" value="Cancle" onclick="Cancle();">
                                    </div>
                            </div>
                         </form>                        
                        </div>                      
                    </div>
                </div>
            </div>
            <!-- #END# Input -->
            
            <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>Category Details</h2>
                                <div class="body table-responsive">
                                    <?php
                                    $Select = "SELECT categoryid, catid, categoryname, description FROM categorymaster where isactive='1';";
//                        echo $Select;
                                    $result_query = mysqli_query($conn, $Select) or die(mysqli_error($conn));
//                    var_dump($result_query);
                                    if (mysqli_num_rows($result_query) != 0) {
                                        $count = 1;
                                        echo'<table class="table table-hover"> 
                                <thead>
                                    <tr>
                                     <th>#</th>
                                        <th>CategoryName</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>   
                                     <tbody>';
                                        while ($rows = mysqli_fetch_array($result_query)) {
                                            $categoryid = $rows['categoryid'];
                                            $categoryname = $rows['categoryname'];
                                            $Description = $rows['description'];

                                            echo ' <tr class="row-item">
                                      <td>' . $count . '</td>
                                        <td>' . $categoryname . '</td>
                                        <td>' . $Description . '</td>
                                       <td><a class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float"
                                       onclick="EditCategory(\'' . $rows['categoryid'] . '\',\'' . $rows['categoryname'] . '\',\'' . $rows['description'] . '\')">
                          <i class="material-icons">mode_edit</i></a></td>                         						
                        <td><a class="btn bg-red btn-circle waves-effect waves-circle waves-float" onclick="ShowDelete(\'' . $rows['categoryid'] . '\')">
                            <i class="material-icons">delete</i></a><td>
                                    </tr> ';
                                            $count ++;
                                        }
                                        $total = $count - 1;
                                        //echo '<script>alert("'.$total.'");</script>';
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
                                         function EditCategory(CategoryID, CategoryName, Description) {

//                                            document.getElementById("hdnCatID").value = categoryid;
                                            document.getElementById("hdnCategoryID").value = CategoryID;
                                            document.getElementById("txt_CategoryName").value = CategoryName;
                                            document.getElementById("txt_Description").value = Description;

                                            if (CategoryID != "") {
                                                document.getElementById("Card_Category").innerHTML = "Edit Product Category";
                                                document.getElementById("btnCategorySubmit").value = "Update";
                                                document.getElementById("editcat").classList.add("focused");
                                                document.getElementById("editdes").classList.add("focused");

                                            }
                                            else {
                                                document.getElementById("Card_Category").innerHTML = "Add Product Category";
                                                document.getElementById("btnCategorySubmit").value = "Add";
                                            }
                                        }
                                        function ShowDelete(categoryid){
                                             var text = "Are you Sure! You want to delete this Record!.";
                                            if (confirm(text) == true) {
                                                var Data = {type : "Category_Delete" , categoryid : categoryid}
//                                              text = "You pressed OK!";
                                               $.ajax({  
                                            method:"POST",  
                                            url:"delete.php",  
                                            data:Data,  
                                           success: function(data){
                                        if(data = "1"){
                                            alert("Category Deleted Successfully!");
                                        window.location.replace("category.php");
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
//                                                    document.getElementById("txtUnit").value = "";
//                                                    document.getElementById("txtDesc").value = "";
                                                document.getElementById("Card_Category").innerHTML = "Add Product Category";
                                                document.getElementById("btnCategorySubmit").value = "Add";
                                                //  });
                                            }
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </section>
</body>
</html>