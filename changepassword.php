<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'connection.php';

$UserID=$_SESSION["UserID"];
if(isset($_POST['btn_ChangePassword'])){
    
    $OldPassword = base64_encode($_POST['txt_OldPassword']);
 $NewPassword =base64_encode($_POST['txt_NewPassword']);
   
if (count($_POST) > 0) {
   
    $result = "SELECT password from userdetails WHERE userid='$UserID'";
  //  echo $result;
      $row = mysqli_query($conn,$result);
    $ur= mysqli_fetch_row($row);
   // echo '<br/>'.$ur[0].'<br/>';
    if ($OldPassword == $ur[0]) {
        $update= "UPDATE userdetails set password='" . $NewPassword . "' WHERE userid='$UserID'";
        $res= mysqli_query($conn, $update);
         echo "<script type='text/javascript'> alert('Your Password is Changed...');"
         . " window.location.replace(\"changepassword.php\");</script>";
    } else
      echo "<script type='text/javascript'> alert('Your Password is not Change...Please Try Again...!!!');"
         . " window.location.replace(\"changepassword.php\");</script>";
}
}
?>
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
                            <h2>
                                Change Password
                            </h2>  
                        </div>
                        <div class="body">
                         <form method="post" enctype="multipart/form-data">
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="password" name="txt_OldPassword" id="txt_OldPassword" class="form-control">
                                            <label class="form-label">Enter Old Password</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="password" name="txt_NewPassword" id="txt_NewPassword" class="form-control">
                                            <label class="form-label">Enter New Password</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="password" name="txt_ConfirmPassword" id="txt_ConfirmPassword" class="form-control">
                                            <label class="form-label">Enter Confirm Password</label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-sm-2">
                                        <input class="btn btn-block bg-green waves-effect" id="btn_ChangePassword" name="btn_ChangePassword" type="submit" value="Submit">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="button" class="btn btn-block bg-green waves-effect" value="Cancle" onclick="Cancle();">
                                    </div>                            
                            </div>
                         </form> 
                         <script>
                                function validatePassword(val_pass){
                                if(val_pass.txt_OldPassword.value == "") 
                                {
                                    val_pass.txt_OldPassword.classList.add('invalid');
                                    return false;
                                }
                                else {
                                    val_pass.txt_OldPassword.classList.remove('invalid');
                                }
                                if(val_pass.txt_NewPassword.value == "") 
                                {
                                    val_pass.txt_NewPassword.classList.add('invalid');
                                    return false;
                                }
                                else {
                                    val_pass.txt_NewPassword.classList.remove('invalid');
                                }
                                 if(val_pass.txt_ConfirmPassword.value == "") 
                                {
                                    val_pass.txt_ConfirmPassword.classList.add('invalid');
                                    return false;
                                }
                                 else {
                                    val_pass.txt_ConfirmPassword.classList.remove('invalid');
                                }
                                if (val_pass.txt_OldPassword.value != val_pass.txt_ConfirmPassword.value) {
                                  val_pass.txt_ConfirmPassword.classList.add('invalid');
                                 return false;  
                                }else{
                                 val_pass.txt_ConfirmPassword.classList.remove('invalid');

                               }
                            }
                            function Cancle() {
                                                //    $("input").click(function () {
                                                $("#d")[0].reset();
//                                                    document.getElementById("txtUnit").value = "";
//                                                    document.getElementById("txtDesc").value = "";
                                                document.getElementById("Card_Role").innerHTML = "Add User Role";
                                                document.getElementById("btnSubmit").value = "Add";
                                                //  });
                                            }
                                </script>
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
    </section> 
</body>