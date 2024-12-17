<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'connection.php';
if (isset($_POST['btnSubmit'])) {
    $Email = $_POST["txtEmail"];
    $Password = base64_encode($_POST["txtPassword"]);
     $select = "SELECT  user.userid, user.email,user.status,user.password,user.profileimage,"
            . " user.mobileno,user.roleid,user.username,user.isactive, role.roleid, role.role"
            . " FROM userdetails user inner join rolemaster role ON  role.role = user.roleid"
            . " WHERE (user.email = '$Email' OR user.mobileno='$Email' OR user.username='$Email') "
            . "AND user.password = '$Password' AND user.isactive = '1' ";
//echo $select;
    $result = mysqli_query($conn, $select)or die(mysqli_error($conn));
//var_dump($result);
    if ($result) {
        $i = $result->fetch_object();

        if ($i != null) {
            $st = $i->status;

            if ($st == "0") {
                $_SESSION["UserID"] = $i->userid;
                $_SESSION["Email"] = $i->email;
                $_SESSION["Password"] = $i->password;
                $_SESSION["ProfileImg"] = $i->profileimage;
                $_SESSION["Role"] = $i->role;
                $_SESSION["PhoneNo"] = $i->mobileno;
                $_SESSION["UserName"] = $i->username;
                $_SESSION["IsActive"] = $i->isactive;
               
                if ($_SESSION["Role"] == 'Admin') {
                    echo "<script type='text/javascript'>window.location.href = 'dashboard.php';</script>";
                } else {
                    echo "<script type='text/javascript'>window.location.href = 'buyprocess.php';</script>";
                }
            } else {
                //echo "<br/><br/>Status  0 part";
                echo "<script type='text/javascript'>
                     alert('You haven\'t verified your account yet. Please verify first then login.');
                               </script>";
            }
        } else {
            echo "<script type='text/javascript'> alert('Your Login Name or Password is invalid... Please try Again');
window.location.replace(\"index.php\");</script>";
        }
    } else {
        echo "<script type='text/javascript'> alert('Your Login Name or Password is invalid... Please try Again');
window.location.replace(\"index.php\");</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sign In | Bootstrap Based Admin Template - Material Design</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Grain Stock</a>
            <small><h4>Management</h4></small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST" onsubmit="return ValLogin(this);">
                    <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" id="txtEmail" name="txtEmail" placeholder="Username"  autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="txtPassword" id="txtPassword" placeholder="Password" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="submit" id="btnSubmit" name="btnSubmit">SIGN IN</button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6 align-right" style="margin-left: 172px;">
                            <a href="forgotpassword.php">Forgot Password?</a>
                        </div>
                    </div>
                </form>
                 <script>
                            function ValLogin(frm) {

                                if (frm.txtEmail.value == "")
                                {
                                    frm.txtEmail.classList.add('invalid');
                                    return false;
                                }
                                else {
                                    frm.txtEmail.classList.remove('invalid');
                                }

                                if (frm.txtPassword.value == "")
                                {
                                    frm.txtPassword.classList.add('invalid');
                                    return false;
                                }
                                else {
                                    frm.txtPassword.classList.remove('invalid');
                                }
                            }
                        </script>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/examples/sign-in.js"></script>
</body>

</html