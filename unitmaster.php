<?php
 require 'connection.php';
    
    if(isset($_POST['btnunitSubmit'])){
     $UnitID = $_POST['hdnUnitID'];
     $UnitName = $_POST['txt_UnitName'];
     $Description = $_POST['txt_Description'];
     
     if($UnitID == ""){
     $Unit_Insert_qry = "INSERT INTO unitmaster (unitid, unit, description) VALUES (UUID(),'$UnitName','$Description')";
    
     $Query_Result = mysqli_query($conn, $Unit_Insert_qry) or die(mysqli_error($conn));
         echo "<script>alert('Unit is successfully Inserted !');window.location.replace('unitmaster.php');</script>";
    }else{
         $Query = "UPDATE unitmaster SET unit='$UnitName',description='$Description', "
                . "updatedate=current_timestamp() WHERE unitid='$UnitID'";
//    echo $Query;
        $Query_result = mysqli_query($conn, $Query)or die(mysqli_error($conn));
       echo "<script> alert ('Unit is successfully Updated !');window.location.replace('unitmaster.php');</script>";
}
    }

?>

<!DOCTYPE html>
<html>
<?php
    include 'header.php';
?>
<body>
        <section class="content">
        <div class="container-fluid">
            <div class="block-header">
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                            <div class="header">
                                <h2 id="Card_Unit">
                                    Add Unit
                                </h2>
                            </div>
                            <div class="body">
                                <form  id="d" class="form_validation" method="POST" enctype="multipart/form-data">
                                      <input type="hidden" id="hdnUnitID" name="hdnUnitID"/>
                                <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line" id="editcat"> 
                                            <input type="text" name="txt_UnitName" id="txt_UnitName" class="form-control">
                                            <label class="form-label">Enter Unit</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line" id="editdes">
                                            <input type="text" name="txt_Description" id="txt_Description" class="form-control">
                                            <label class="form-label">Enter Description of Unit...</label>
                                        </div>
                                    </div>
                                </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-sm-3">
                                                    <input class="btn btn-block bg-green waves-effect" id="btnunitSubmit" name="btnunitSubmit" type="submit" value="Add">
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="button" class="btn btn-block bg-green waves-effect" value="Cancle" onclick="Cancle();">
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
                                <h2>Unit Details</h2>
                                <div class="body table-responsive"> 
                                    <?php
                                    $Select = "SELECT unitid,unit,description FROM unitmaster";
                                    $result_query = mysqli_query($conn, $Select) or die(mysqli_error($conn));
//                    var_dump($result_query);
                                    if (mysqli_num_rows($result_query) != 0) {
                                        $count = 1;
                                        echo'<table class="table table-hover"> 
                                <thead>
                                    <tr>
                                     <th>#</th>
                                        <th>Unit</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>   
                                     <tbody>';
                                        while ($rows = mysqli_fetch_array($result_query)) {
                                            $UnitID = $rows['unitid'];
                                            $Unit = $rows['unit'];
                                            $Description = $rows['description'];

                                            echo ' <tr class="row-item">
                                      <td>' . $count . '</td>
                                        <td>' . $Unit . '</td>
                                        <td>' . $Description . '</td>
                                       <td><a class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float"
                                       onclick="EditUnit(\'' . $rows['unitid'] . '\',\'' . $rows['unit'] . '\','
                                            . '\'' . $rows['description'] . '\')">
                          <i class="material-icons">mode_edit</i></a></td>                         						
                        <td><a class="btn bg-red btn-circle waves-effect waves-circle waves-float" 
                        onclick="ShowDelete(\'' . $rows['unitid'] . '\');"><i class="material-icons">delete</i></a><td>
                                    </tr> ';
                                            $count ++;
                                        }
//                                        $total = $count - 1;
                                        echo "<input type='hidden' id='counts' name='counts' value='$count'/> ";
                                        echo '</tbody>
                            </table>';
                                    } else {
                                        echo '<div class="alert alert-info"><strong><large>No record found!!.</large></strong></div>';
                                    }
                                    ?>
                                    <ul class="pagination pagination-sm custom-pagination" id="ulPage"></ul>
                                    <!--prepare necessary element attributes to pass parameters for pagination--> 
                                    <input type="hidden" id="itemCount" value="" />
                                    <input type="hidden" id="pageLimit" value="5" />
                                    <script>
                                         function EditUnit(UnitID, UnitName, Description) {

//                                            document.getElementById("hdnCatID").value = CatID;
                                            document.getElementById("hdnUnitID").value = UnitID;
                                            document.getElementById("txt_UnitName").value = UnitName;
                                            document.getElementById("txt_Description").value = Description;

                                            if (UnitID != "") {
                                                document.getElementById("Card_Unit").innerHTML = "Edit Unit";
                                                document.getElementById("btnunitSubmit").value = "Update";
                                                document.getElementById("editcat").classList.add("focused");
                                                document.getElementById("editdes").classList.add("focused");

                                            }
                                            else {
                                                document.getElementById("Card_Unit").innerHTML = "Add Unit";
                                                document.getElementById("btnunitSubmit").value = "Add";
                                            }
                                        }
                                        function ShowDelete(UnitID){
                                             var text = "Are you Sure! You want to delete this Record!.";
                                            if (confirm(text) == true) {
                                                var Data = {type : "Unit_Delete" , UnitID : UnitID}
//                                              text = "You pressed OK!";
                                               $.ajax({  
                                            method:"POST",  
                                            url:"delete.php",  
                                            data:Data,  
                                           success: function(data){
                                        if(data = "1"){
                                            alert("Unit Deleted Successfully!");
                                            window.location.replace("unitmaster.php");
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
                                                document.getElementById("Card_Unit").innerHTML = "Add Unit";
                                                document.getElementById("btnunitSubmit").value = "Add";
                                                //  });
                                            }
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- #END# Input -->
        </div>
</section>
</body>
</html>