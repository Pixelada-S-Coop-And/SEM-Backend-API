<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>SEM | Panel de administración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Panel de administración de SEM" name="description" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo site_url('assets/images/favicon.png'); ?>">

    <!-- App css -->
    <link href="<?php echo site_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/css/icons.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/css/theme.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/switchery/switchery.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.css'); ?>" rel="stylesheet" type="text/css" />
    
    <link href="<?php echo site_url('assets/plugins/daterangepicker/daterangepicker.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/dropify/dropify.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/datetimepicker/bootstrap-datetimepicker.css'); ?>" rel="stylesheet" />
    <link href="<?php echo site_url('assets/plugins/datatables/dataTables.bootstrap4.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/datatables/responsive.bootstrap4.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/datatables/buttons.bootstrap4.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/datatables/select.bootstrap4.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/quill/quill.core.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/quill/quill.bubble.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/quill/quill.snow.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo site_url('assets/plugins/leaflet/leaflet.css'); ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo site_url('assets/plugins/leaflet/search/leaflet-search.css'); ?>" />
    <link rel="stylesheet" href="<?php echo site_url('assets/js/jquery-ui/jquery-ui.css'); ?>" />
    <link href="<?php echo site_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css" />

      <!-- jQuery  -->
    <script src="<?php echo site_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/js/metismenu.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/js/waves.js'); ?>"></script>
    <script src="<?php echo site_url('assets/js/simplebar.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/js/jquery-ui/jquery-ui.min.js'); ?>"></script>

    <!-- Sparkline Js-->
    <script src="<?php echo site_url('assets/plugins/jquery-sparkline/jquery.sparkline.min.js'); ?>"></script>

    <!-- Morris Js-->
    <script src="<?php echo site_url('assets/plugins/morris-js/morris.min.js'); ?>"></script>
    <!-- Raphael Js-->
    <script src="<?php echo site_url('assets/plugins/raphael/raphael.min.js'); ?>"></script>

    <script src="<?php echo site_url('assets/plugins/switchery/switchery.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js'); ?>"></script>

    <script src="<?php echo site_url('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/moment/moment.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/daterangepicker/daterangepicker.js'); ?>"></script>
    
    <script src="<?php echo site_url('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/dropify/dropify.min.js'); ?>"></script>

    <script src="<?php echo site_url('assets/plugins/datetimepicker/bootstrap-datetimepicker.js'); ?>"></script>
    
    <script src="<?php echo site_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/datatables/dataTables.bootstrap4.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/datatables/dataTables.responsive.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/datatables/responsive.bootstrap4.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/datatables/dataTables.buttons.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/datatables/buttons.bootstrap4.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/datatables/buttons.html5.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/datatables/buttons.flash.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/datatables/buttons.print.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/datatables/dataTables.keyTable.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/datatables/dataTables.select.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/datatables/pdfmake.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/datatables/vfs_fonts.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/katex/katex.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/quill/quill.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/leaflet/leaflet.js'); ?>"></script>
    <script src="<?php echo site_url('assets/plugins/leaflet/search/leaflet-search.js'); ?>"></script>
    <!-- Custom Js -->
    <script src="<?php echo site_url('assets/pages/dashboard-demo.js'); ?>"></script>

    <!-- App js -->
    <script src="<?php echo site_url('assets/js/theme.js'); ?>"></script>
    <script src="<?php echo site_url('assets/js/custom.js'); ?>"></script>
   

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
    <?php echo $current; require_once 'main-menu.php'; ?>

     <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
        <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm mr-2 d-lg-none header-item" id="vertical-menu-btn">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>

                        <div class="header-breadcumb">
                        <!--  <h6 class="header-pretitle d-none d-md-block">Pages <i class="dripicons-arrow-thin-right"></i> Dashboard</h6> -->
                        <!--  <?php if(!empty($title)): ?><h2 class="header-title"><?php echo $title; ?></h2><?php endif; ?> -->
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        
                        <!-- <a href="<?php echo site_url('admin/juegos'); ?>" style="color:#fff;" class="btn btn-primary d-none d-lg-block ml-2">
                            <i class="mdi mdi-pencil-outline mr-1"></i> Juegos
                        </a>-->
<!--
                        <div class="dropdown d-inline-block ml-2">
                            <button type="button" class="btn header-item noti-icon" id="page-header-notifications-dropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-bell-outline"></i>
                                <span class="badge badge-danger badge-pill">6</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0"> Notifications </h6>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#!" class="small"> View All</a>
                                        </div>
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 230px;">
                                    <a href="" class="text-reset">
                                        <div class="media py-2 px-3">
                                            <img src="<?php echo site_url('assets/images/users/avatar-2.jpg'); ?>"
                                                class="mr-3 rounded-circle avatar-xs" alt="user-pic">
                                            <div class="media-body">
                                                <h6 class="mt-0 mb-1">Samuel Coverdale</h6>
                                                <p class="font-size-12 mb-1">You have new follower on Instagram</p>
                                                <p class="font-size-12 mb-0 text-muted"><i class="mdi mdi-clock-outline"></i> 2 min ago</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="" class="text-reset">
                                        <div class="media py-2 px-3">
                                            <div class="avatar-xs mr-3">
                                                <span class="avatar-title bg-success rounded-circle">
                                                    <i class="mdi mdi-cloud-download-outline"></i>
                                                </span>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="mt-0 mb-1">Download Available !</h6>
                                                <p class="font-size-12 mb-1">Latest version of admin is now available. Please download here.</p>
                                                <p class="font-size-12 mb-0 text-muted"><i class="mdi mdi-clock-outline"></i> 4 hours ago</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="" class="text-reset">
                                        <div class="media py-2 px-3">
                                            <img src="<?php echo site_url('assets/images/users/avatar-3.jpg'); ?>"
                                                class="mr-3 rounded-circle avatar-xs" alt="user-pic">
                                            <div class="media-body">
                                                <h6 class="mt-0 mb-1">Victoria Mendis</h6>
                                                <p class="font-size-12 mb-1">Just upgraded to premium account.</p>
                                                <p class="font-size-12 mb-0 text-muted"><i class="mdi mdi-clock-outline"></i> 1 day ago</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2 border-top">
                                    <a class="btn btn-sm btn-light btn-block text-center" href="javascript:void(0)">
                                        <i class="mdi mdi-arrow-down-circle mr-1"></i> Load More..
                                    </a>
                                </div>
                            </div>
                        </div> -->
<?php   $user = $_SESSION['user']; ?>
                        <div class="dropdown d-inline-block ml-2">
                            <button type="button" class="btn header-item" id="page-header-user-dropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i id="icono-user" class="feather-user"></i><!--<img class="rounded-circle header-profile-user" src="<?php echo site_url('assets/images/users/avatar-1.jpg'); ?>"
                                    alt="Header Avatar"> -->
                                <span class="d-none d-sm-inline-block ml-1"><?php echo $user->name; ?></span>
                                <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">

                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="<?php echo site_url('admin/users/edit/'.$user->id); ?>">
                                    <span>Perfil</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="<?php echo site_url('admin/logout'); ?>">
                                    <span>Cerrar sesión</span>
                                </a>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </header>