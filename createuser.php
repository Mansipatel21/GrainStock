<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
    include 'header.php';
    require 'connection.php';
    
    if (isset($_POST["btnSubmit"])) {
    $UserName = $_POST['txtUN'];
    $MobileNo = $_POST['txtMobileNo'];
    $Email = $_POST['txtEmail'];
    $RoleID = $_POST['ddlRole'];   
    $Password = $_POST['txtPassword'];
    $RetypePassword = $_POST['txtRetypePassword'];
    $hash = md5(rand(0, 1000));

    $PasswordDefault = base64_encode('Pass@123');
    $status = 0;
    $isactive = '0';

    if( !empty($_FILES["file"]["name"])){
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


    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            
            // Insert image file name into database
//            $insert = $db->query("INSERT into images (file_name, uploaded_on) VALUES ('".$fileName."', NOW())");
             $User_Insert_qry = "INSERT INTO userdetails (userid, username, mobileno, roleid, email, password, profileimage, isactive, activationcode, status) "
             . "VALUES (UUID(),'$UserName', '$MobileNo', '$RoleID', '$Email', '$PasswordDefault','$targetFilePath',"
                . "'$isactive','$hash', '$status')";
     $Query_Result = mysqli_query($conn, $User_Insert_qry) or die(mysqli_error($conn));
            if($Query_Result){
        echo "<script>alert('The file ".$fileName. " has been uploaded successfully.');window.location.replace('createuser.php');</script>";
            }else{
        echo "<script>alert('File upload failed, please try again.');window.location.replace('createuser.php');</script>";
            } 
        }else{
        echo "<script>alert('Sorry, there was an error uploading your file.');window.location.replace('createuser.php');</script>";
        }
    }else{
        echo "<scripy>alert('Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.');window.location.replace('createuser.php');</script>";
    }

    }else{
        echo "<script>alert('Product is successfully Inserted !');window.location.replace('createuser.php');</script>";
}
    

// Display status message
//echo $statusMsg;
    }
?>
<body class="theme-green">
    <section class="content">
        <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                                <h2 id="Card_User">
                                   Create User
                                </h2>
                        </div>
                          <div class="body">
                    
                          <form id="d" class="form_validation" id="frmFileUpload" method="POST" enctype="multipart/form-data" 
                                      onsubmit="return ValUser(this);" >
                                    <div class="row clearfix">
                                        <div class="col-sm-12">
                                            <div class="col-sm-6">                            
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="hidden" name="hdnImgBase" id="hdnImgBase" value=""/>
                                                        <input type="hidden" name="hdnImgPath" id="hdnImgPath" value=""/>
                                                        <input type="hidden" name="hdnUserID" id="hdnUserID"/>
                                                        <input type="text" name="txtUN" id="txtUN"
                                                               class="form-control" placeholder="UserName"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">                            
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="txtMobileNo" id="txtMobileNo"
                                                               class="form-control" placeholder="MobileNo"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">                            
                                                <select class="form-control show-tick" name="ddlRole" id="ddlRole">
                                                    <option value="-1">-- Select Role --</option>
                                                    <?php
                                                    $Select = mysqli_query($conn, "SELECT roleid,role,isactive FROM rolemaster WHERE isactive='1'");
                                                    while ($row = mysqli_fetch_array($Select)) {
                                                        ?>
                                                        <option value="<?php echo $row["role"]; ?>">
                                                            <?php echo $row["role"]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">                            
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" name="txtEmail" id="txtEmail"
                                                               class="form-control" placeholder="Email ID"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">                            
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="password" name="txtPassword" id="txtPassword"
                                                               class="form-control" placeholder="Password"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">                            
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="password" name="txtRetypePassword" id="txtRetypePassword"
                                                               class="form-control" placeholder="Retype Password"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12"> 
                                                <h5>Profile Image</h5>
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
                                            function ValUser(frm) {

                                                if (frm.txtUN.value == "") {
                                                    frm.txtUN.classList.add('invalid');
                                                    return false;
                                                } else {
                                                    frm.txtUN.classList.remove('invalid');
                                                }

                                                no = /^\d{10}$/;
                                                if (!no.test(frm.txtMobileNo.value)) {
                                                    frm.txtMobileNo.classList.add('invalid');
                                                    return false;
                                                } else {
                                                    frm.txtMobileNo.classList.remove('invalid');
                                                }

                                                if (frm.ddlRole.value == "-1") {
                                                    var btn = $("button[data-id='ddlRole']");
                                                    btn.addClass("invalid");
//                                                    frm.ddlRole.classList.add('invalid');
                                                    return false;
                                                } else {
                                                    var btn = $("button[data-id='ddlRole']");
                                                    btn.removeClass("invalid");
//                                                    frm.ddlRole.classList.remove('invalid');
                                                }
                                                mi = /^([_a-z0-9_]+)(\.[_a-z0-9_]+)*@([a-z0-9_]+)(\.[a-z0-9_]+)*(\.[a-z]{2,4})$/;
                                                if (!mi.test(frm.txtEmail.value)) {
                                                    frm.txtEmail.classList.add('invalid');
                                                    return false;
                                                } else {
                                                    frm.txtEmail.classList.remove('invalid');
                                                }

                                                pw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{4,15}$/;
                                                if (!pw.test(frm.txtPassword.value)) {
                                                    frm.txtPassword.classList.add('invalid');
                                                    return false;
                                                } else {
                                                    frm.txtPassword.classList.remove('invalid');
                                                }

                                                if (frm.txtRetypePassword.value != frm.txtPassword.value) {
                                                    frm.txtRetypePassword.classList.add('invalid');
                                                    return false;
                                                } else {
                                                    frm.txtRetypePassword.classList.remove('invalid');

                                                }
                                            }

                                            // $(document).ready(function () {
                                            function Cancle() {
                                                //    $("input").click(function () {
                                                $("#d")[0].reset();
                                                //  document.getElementById("txtUnit").value = "";
                                                //  document.getElementById("txtDesc").value = "";
                                                document.getElementById("Card_User").innerHTML = "Create User";
                                                document.getElementById("btnSubmit").value = "Add";
                                                //  });
                                            }
                                            // });
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
        <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>Activate/Deactivate User</h2>
                                <span id="message"></span>
                                <div class="body table-responsive" id="user_data"> 
                                    <?php
                                    $Select = "SELECT  user.userid, user.email,user.status,user.password,user.profileimage,"
                                            . " user.mobileno,user.roleid,user.username,user.isactive, role.roleid, role.role"
                                            . " FROM userdetails user inner join rolemaster role ON  role.role = user.roleid;";
//                            echo $Select;
                                    $result_query = mysqli_query($conn, $Select) or die(mysqli_error($conn));
//                    var_dump($result_query);
                                    if (mysqli_num_rows($result_query) != 0) {
                                        $count = 1;
                                        $total = "";
                                        echo'<table class="table table-hover"> 
                                <thead>
                                    <tr>
                                     <th>#</th>
                                        <th>ProfileImage</th>
                                         <th>UserName</th>
                                        <th>MobileNo</th>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>   
                                     <tbody>';
                                        while ($rows = mysqli_fetch_array($result_query)) {
                                            $Userid = $rows['userid'];
                                            $ProfileImg = $rows['profileimage'];
                                            $UserName = $rows['username'];
                                            $MobileNo = $rows['mobileno'];
                                            $Role = $rows['role'];
                                            $Email = $rows['email'];
                                            $IsActive = $rows['isactive'];

                                            $img_profile = substr($ProfileImg, strpos($ProfileImg, "uploads"), strlen($ProfileImg));
                                            if (!file_exists($img_profile)) {
                                                $img_profile = "";
                                            }
                                            echo ' <tr class="row-item">
                                      <td>' . $count . '</td>
                                           <td><img src="' . $img_profile . '" alt="" width="48" height="48" title="admin" id="user-profile" class="img-circle"></td> '
                                            . '<td>' . $UserName . '</td>
                                            <td>' . $MobileNo . '</td><td>' . $Role . '</td><td>' . $Email . '</td>';
                                            if ($IsActive == "1") {
                                        echo ' <td><a class="btn btn-success waves-effect" 
                                       onclick="UserActivate(\'' . $rows['userid'] . '\',\'Activate\');">Active
                          <i class="material-icons">verified_user</i></a></td>';
                                           } else {
                                        echo ' <td><a class="btn btn-danger waves-effect"
                                       onclick="UserActivate(\'' . $rows['userid'] . '\',\'DeActivate\');">DeActivate
                          <i class="material-icons">verified_user</i></a></td>';
                                    }
                                            echo'</tr>';

                                            $count ++;
                                        }
                                        $total = $count - 1;
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
                                    function UserActivate(UserID, Type) {
                                    if (Type == "DeActivate") {
                                        var text = "You want to Activate these account";
                                    } else {
                                        var text = "You want to DeActive these account";
                                    }
                                    if (confirm(text) == true) {
                                        var Data = {type: "User_ActivateDetactive", UserID: UserID, ActivateType: Type}
//                                              text = "You pressed OK!";
                                        $.ajax({
                                            method: "POST",
                                            url: "delete.php",
                                            data: Data,
                                            success: function (data) {
                                                if (data == "1") {
                                                    alert("User Activated Successfully!");
                                                    window.location.reload();
                                                } else {
                                                    alert("User DeActivated Successfully!");
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
                                                //  document.getElementById("txtUnit").value = "";
                                                //  document.getElementById("txtDesc").value = "";
                                                document.getElementById("Card_User").innerHTML = "Create User";
                                                document.getElementById("btnSubmit").value = "Add";
                                                
                                                 var index = document.getElementById("ddlRole").selectedIndex;
                                                var btn = $("button[data-id='ddlRole']");
                                                var catname = $("#ddlRole")[0].selectedOptions[0].text;
                                                var li = $("#ddlRole").siblings("div").children().children("li[data-original-index='" +
                                                        index + "']");
                                                li.addClass("selected");
                                                li.siblings().removeClass();

                                                btn[0].setAttribute("title", catname);
                                                btn.children()[0].innerText = catname;
                                                //  });
                                            }
                                    </script>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
    </section>