<?php

 require 'connection.php';
    
    if(isset($_POST['btnrolemasterSubmit'])){
     $RoleID = $_POST['hdnRoleID'];
     $Role = $_POST['txt_Role'];
     if ($RoleID == ""){
     $Role_Insert_qry = "INSERT INTO rolemaster (roleid, role, isactive) VALUES (UUID(),'$Role','1')";
    
     $Query_Result = mysqli_query($conn, $Role_Insert_qry) or die(mysqli_error($conn));
         echo "<script>alert('Role is successfully Inserted !');window.location.replace('rolemaster.php');</script>";
         
    }else{
     $Query = "UPDATE rolemaster SET role='$Role',"
                . "updateddate=current_timestamp() WHERE roleid='$RoleID'";
        $Query_result = mysqli_query($conn, $Query)or die(mysqli_error($conn));
        echo "<script>alert('Role is successfully Updated !');window.location.replace('rolemaster.php');</script>";
    }
}
     include 'header.php';
?>
<body class="theme-green">
    <section class="content">
        <div class="card">
                            <div class="header">
                                <h2 id="Card_Role">
                                    Add User Role
                                </h2>
                            </div>
                            <div class="body">
                                <form id="d" class="form_validation" method="POST" enctype="multipart/form-data">
                                   
                                    <div class="row clearfix">
                                        <div class="col-sm-6">                            
                                            <div class="form-group form-float">
                                                <div class="form-line col-sm-6" id="editrole">
                                                    <input type="hidden" name="hdnRoleID" id="hdnRoleID">
                                                    <input type="text" name="txt_Role" id="txt_Role" class="form-control" placeholder="Enter User Role">
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-sm-3">
                                                    <input class="btn btn-block bg-green waves-effect" id="btnrolemasterSubmit" name="btnrolemasterSubmit" type="submit" value="Add">
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
        <div class="row clearfix">
                    <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6">
                        <div class="card">
                            <div class="header">
                                <h2>User Role Details</h2>
                                <div class="body table-responsive">
                                    <?php
                                    $Select = "SELECT roleid,role FROM rolemaster WHERE isactive='1'";
//                        echo $Select;
                                    $result_query = mysqli_query($conn, $Select) or die(mysqli_error($conn));
//                    var_dump($result_query);
                                    if (mysqli_num_rows($result_query) != 0) {
                                        $count = 1;
                                        echo'<table class="table table-hover"> 
                                <thead>
                                    <tr>
                                     <th>#</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>   
                                     <tbody>';
                                        while ($rows = mysqli_fetch_array($result_query)) {
                                            $roleid= $rows['roleid'];
                                            $role = $rows['role'];

                                            echo ' <tr class="row-item">
                                      <td>' . $count . '</td>
                                        <td>' . $role . '</td>
                                       <td><a class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float"
                                       onclick="EditRole(\'' . $rows['roleid'] . '\',\'' . $rows['role'] . '\')">
                          <i class="material-icons">mode_edit</i></a></td>                         						
                        <td><a class="btn bg-red btn-circle waves-effect waves-circle waves-float"
                        onclick="ShowDelete(\'' . $rows['roleid'] . '\');"><i class="material-icons">delete</i></a><td>
                                    </tr>  ';
                                            $count ++;
                                        }
//                                        $total = $count - 1;
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
                                        function EditRole(RoleID, Role) {

                                            document.getElementById("hdnRoleID").value = RoleID;
                                            document.getElementById("txt_Role").value = Role;

                                            if (RoleID != "") {
                                                document.getElementById("Card_Role").innerHTML = "Edit User Role";
                                                document.getElementById("btnrolemasterSubmit").value = "Update";
                                                document.getElementById("editrole").classList.add("focused");
                                            }
                                            else {
                                                document.getElementById("Card_Role").innerHTML = "Add User Role";
                                                document.getElementById("btnrolemasterSubmit").value = "Add";
                                            }
                                        }
                                        function ShowDelete(roleid){
                                             var text = "Are you Sure! You want to delete this Record!.";
                                            if (confirm(text) == true) {
                                                var Data = {type : "Role_Delete" , roleid : roleid}
//                                              text = "You pressed OK!";
                                               $.ajax({  
                                            method:"POST",  
                                            url:"delete.php",  
                                            data:Data,  
                                           success: function(data){
                                        if(data = "1"){
                                            alert("Role Deleted Successfully!");
                                            window.location.reload();
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
                                                document.getElementById("Card_Role").innerHTML = "Add User Role";
                                                document.getElementById("btnSubmit").value = "Add";
                                                //  });
                                            }
//                                            });
                                    </script>
                                </div>       
                            </div>
                        </div>
                    </div>
                </div>
        </section>