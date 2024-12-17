<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$ProfileImg = $_SESSION["ProfileImg"];
$img_profile = substr($ProfileImg, strpos($ProfileImg, "uploads"), strlen($ProfileImg));
if (!file_exists($img_profile)) {
    $img_profile = $NoImgPath;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Welcome To | Bootstrap Based Admin Template - Material Design</title>
        <!-- Favicon-->
        <link rel="icon" href="favicon.ico" type="image/x-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

        <!-- Bootstrap Core Css -->
        <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

        <!-- Dropzone Css -->
        <link href="plugins/dropzone/dropzone.css" rel="stylesheet">

        <!-- Bootstrap Select Css -->
        <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

        <!-- Waves Effect Css -->
        <link href="plugins/node-waves/waves.css" rel="stylesheet" />

        <!-- Animation Css -->
        <link href="plugins/animate-css/animate.css" rel="stylesheet" />

        <!-- Morris Chart Css-->
        <link href="plugins/morrisjs/morris.css" rel="stylesheet" />

        <!-- Custom Css -->
        <link href="css/style.css" rel="stylesheet">

        <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
        <link href="css/themes/all-themes.css" rel="stylesheet" />
    </head>

    <body class="theme-green">
        <!-- Page Loader -->
        <div class="page-loader-wrapper">
            <div class="loader">
                <div class="preloader">
                    <div class="spinner-layer pl-red">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
                <p>Please wait...</p>
            </div>
        </div>
        <!-- #END# Page Loader -->
        <!-- Overlay For Sidebars -->
        <div class="overlay"></div>
        <!-- #END# Overlay For Sidebars -->
        <!-- Search Bar -->
        <div class="search-bar">
            <div class="search-icon">
                <i class="material-icons">search</i>
            </div>
            <input type="text" placeholder="START TYPING...">
            <div class="close-search">
                <i class="material-icons">close</i>
            </div>
        </div>
        <!-- #END# Search Bar -->
        <!-- Top Bar -->
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                    <a href="javascript:void(0);" class="bars"></a>
                    <a class="navbar-brand" href="index.html">Grain Stock Management</a>
                </div>
                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Notifications -->
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                                <img src="<?php echo $img_profile; ?>" alt="" width="48" height="48" title="admin" 
                                     id="user-profile" class="img-circle">
                                <?php echo $_SESSION['UserName']; ?> <i class="material-icons"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="editprofile.php" class=" waves-effect waves-block"><i class="material-icons">person</i>Profile</a></li>
                                <li><a href="changepassword.php" class=" waves-effect waves-block"><i class="material-icons">lock</i>Change Password</a></li>
                                <li><a href="index.php" class=" waves-effect waves-block"><i class="material-icons">input</i>Sign Out</a></li>
                            </ul>
                        </li>
                        <!-- #END# Notifications -->

                    </ul>
                </div>
            </div>
        </nav>
        <!-- #Top Bar -->
        <section>
            <!-- Left Sidebar -->
            <aside id="leftsidebar" class="sidebar">
                <!-- Menu -->
                <div class="menu">
                    <ul class="list">
                        <li class="active">
                            <a href="dashboard.php">
                                <i class="material-icons">home</i>
                                <span>Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">widgets</i>
                                <span>Master</span>
                            </a>
                            <ul class="ml-menu">
                                <li>
                                    <a href="unitmaster.php">Unit Master</a>
                                </li>
                                <li>
                                    <a href="category.php">Categories</a>
                                </li>
                                <li>
                                    <a href="productdetails.php">Product Details</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="menu-toggle">
                                        <span>User Master</span>
                                    </a>
                                    <ul class="ml-menu">
                                        <li>
                                            <a href="rolemaster.php">Role Master</a>
                                        </li>
                                        <li>
                                            <a href="createuser.php">Create User</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="buyprocess.php">
                                <i class="material-icons">add_shopping_cart</i>
                                <span>Warehouse Inward</span>
                            </a>
                        </li> 
                        <li>
                            <a href="productdistribution.php">
                                <i class="material-icons">swap_horizontal_circle</i>
                                <span>Product Distribution</span>
                            </a>
                        </li>
                        <li>
                            <a href="billingdetails.php">
                                <i class="material-icons">shopping_basket</i>
                                <span>Product Selling</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">content_paste</i>
                                <span>Reports</span>
                            </a>
                            <ul class="ml-menu">
                                <li>
                                    <a href="inwardreport.php">Inward Report</a>
                                </li>
                                <li>
                                    <a href="outwardreport.php">Outward Report</a>
                                </li>
                                <li>
                                    <a href="">Billing Report</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- #Menu -->
                <!-- Footer -->
                <div class="legal">
                    <div class="copyright">
                        &copy; 2016 - 2017 <a href="javascript:void(0);">AdminBSB - Material Design</a>.
                    </div>
                    <div class="version">
                        <b>Version: </b> 1.0.5
                    </div>
                </div>
                <!-- #Footer -->
            </aside>
            <!-- #END# Left Sidebar -->

        </section>

        <!-- Jquery Core Js -->
        <script src="plugins/jquery/jquery.min.js"></script>

        <!-- Bootstrap Core Js -->
        <script src="plugins/bootstrap/js/bootstrap.js"></script>

        <!-- Select Plugin Js -->
        <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

        <!-- Slimscroll Plugin Js -->
        <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

        <!-- Waves Effect Plugin Js -->
        <script src="plugins/node-waves/waves.js"></script>

        <!-- Jquery CountTo Plugin Js -->
        <script src="plugins/jquery-countto/jquery.countTo.js"></script>

        <!-- Morris Plugin Js -->
        <script src="plugins/raphael/raphael.min.js"></script>
        <script src="plugins/morrisjs/morris.js"></script>

        <!-- ChartJs -->
        <script src="plugins/chartjs/Chart.bundle.js"></script>

        <!-- Flot Charts Plugin Js -->
        <script src="plugins/flot-charts/jquery.flot.js"></script>
        <script src="plugins/flot-charts/jquery.flot.resize.js"></script>
        <script src="plugins/flot-charts/jquery.flot.pie.js"></script>
        <script src="plugins/flot-charts/jquery.flot.categories.js"></script>
        <script src="plugins/flot-charts/jquery.flot.time.js"></script>

        <!-- Sparkline Chart Plugin Js -->
        <script src="plugins/jquery-sparkline/jquery.sparkline.js"></script>

        <script src="plugins/jquery-datatable/jquery.dataTables.js" type="text/javascript"></script>
        <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js" type="text/javascript"></script>
        <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
        <script src="plugins/jquery-datatable/extensions/export/buttons.flash.min.js" type="text/javascript"></script>
        <script src="plugins/jquery-datatable/extensions/export/jszip.min.js" type="text/javascript"></script>
        <script src="plugins/jquery-datatable/extensions/export/pdfmake.min.js" type="text/javascript"></script>
        <script src="plugins/jquery-datatable/extensions/export/vfs_fonts.js" type="text/javascript"></script>
        <script src="plugins/jquery-datatable/extensions/export/buttons.html5.min.js" type="text/javascript"></script>
        <script src="plugins/jquery-datatable/extensions/export/buttons.print.min.js" type="text/javascript"></script>
        <script src="plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js" type="text/javascript"></script>
        <script src="js/pages/tables/jquery-datatable.js" type="text/javascript"></script>

        <!-- Custom Js -->
        <script src="js/admin.js"></script>
        <script src="js/pages/index.js"></script>

        <!-- Demo Js -->
        <script src="js/demo.js"></script>
    </body>

</html>