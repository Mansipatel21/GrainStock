<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
include 'header.php';
require 'connection.php';

$UserID = $_SESSION["UserID"];

if (isset($_POST['btnSubmit'])) {
    $User_Name = $_POST['txtUN'];
    $MobileNo = $_POST['txtMobileNo'];
    $EmailID = $_POST['txtEmail'];
    $HdnImgPath = $_POST['HdnImgPath'];

    if (!empty($_FILES["file"]["name"])) {
        $basedir = __DIR__;
        $basedir = str_replace("\\", "/", $basedir);
        $target_dir = $basedir . "/uploads/";
        __DIR__ . DIRECTORY_SEPARATOR . "<br/>" . $target_dir;
        if (!file_exists($target_dir))
            mkdir($target_dir);
        $target_dir .= "Profile/Img/";

        //$targetDir = "uploads/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $target_dir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        if ($fileType == "jpg" || $fileType == "png" || $fileType == "jpeg" || $fileType == "gif" || $fileType == "pdf") {
            if (move_uploaded_file($_FILES['file']["tmp_name"], $targetFilePath)) {

                if ($ProfileImg != "" || $ProfileImg != NULL) {
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

    $Query_Product = "UPDATE userdetails SET username='$User_Name',email='$EmailID',"
            . "mobileno='$MobileNo',profileimage='$HdnImgPath', updateddate=current_timestamp() WHERE userid='$UserID'";
    $Query_Result = mysqli_query($conn, $Query_Product)or die(mysqli_error($conn));
    echo "<script> alert ('Profile is successfully Updated !');window.location.replace('editprofile.php');</script>";
}
?>
<body class="theme-green">
    <section class="content">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2 id="Card_Unit">
                            Edit Profile
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
                                                <?php
                                                $Select_Product = "SELECT  userid, username, mobileno, roleid, email, password,"
                                                        . " profileimage, isactive, activationcode, status FROM userdetails"
                                                        . " WHERE userid = '$UserID';";
                                                $result_query = mysqli_query($conn, $Select_Product) or die(mysqli_error($conn));
                                                if (mysqli_num_rows($result_query) != 0) {
                                                    while($rows = mysqli_fetch_array($result_query)){
                                                    {
                                                        $UserName = $rows["username"];
                                                        $PhoneNo = $rows["mobileno"];
                                                        $Email = $rows["email"];
                                                        $img_profile = $rows["profileimage"];
                                                        $ProfileImg = substr($img_profile, strpos($img_profile, "uploads"), strlen($img_profile));
                                                        if (!file_exists($ProfileImg)) {
                                                            $ProfileImg = $NoImgFound;
                                                        }
                                                    }
                                                    }
                                                }
                                                ?>
                                                <input type="hidden" name="hdnImgBase" id="hdnImgBase" value=""/>
                                                <input type="hidden" name="hdnImgPath" id="hdnImgPath" value=""/>
                                                <input type="hidden" name="hdnUserID" id="hdnUserID"/>
                                                <input type="text" name="txtUN" id="txtUN"
                                                       class="form-control" placeholder="UserName" value="<?php echo $UserName; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">                            
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="txtMobileNo" id="txtMobileNo"
                                                       class="form-control" placeholder="MobileNo" value="<?php echo $PhoneNo; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">                            
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="txtEmail" id="txtEmail"
                                                       class="form-control" placeholder="Email ID" value="<?php echo $Email; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12"> 
                                        <h5>Profile Image</h5>
                                        <div class="form-group">
                                            <div id="preview_img"></div> 
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0px">
                                            <div id="Uploaded_img">
                                                <img src="<?php echo $ProfileImg; ?>" alt="">
                                            </div>
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
                                                <input name="HdnImgPath" type="hidden" id="HdnImgPath" value="<?php echo $ProfileImg; ?>"/>
                                                 <input name="file" type="file" id="file" value=""/>
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
                                                   id="btnSubmit" name="btnSubmit" type="submit" value="Update"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>  
                    </div>
                </div>
            </div>
        </div>  
    </section>