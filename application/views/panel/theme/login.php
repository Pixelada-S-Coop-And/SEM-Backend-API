<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Acceder | SEM Administración</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="MyraStudio" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="<?php echo site_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/css/icons.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/css/theme.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo site_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css" />
        
        <!-- jQuery  -->
        <script src="<?php echo site_url('assets/js/jquery.min.js'); ?>"></script>
        <script src="<?php echo site_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
        <script src="<?php echo site_url('assets/js/metismenu.min.js'); ?>"></script>
        <script src="<?php echo site_url('assets/js/waves.js'); ?>"></script>
        <script src="<?php echo site_url('assets/js/simplebar.min.js'); ?>"></script>

        <!-- App js -->
        <script src="<?php echo site_url('assets/js/theme.js'); ?>"></script>
  
    </head>

    <body>

        <div class="bg-primary" id="login-page">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-center min-vh-100">
                            <div class="w-100 d-block bg-white shadow-lg rounded my-5">
                                <div class="row">
                                    <div id="autismo-logo-login" class="col-lg-5 d-none d-lg-block bg-login rounded-left" ></div>
                                    <div class="col-lg-7">
                                        <div class="p-5">
                                            <!--<div class="text-center">
                                                <a href="index.html" class="d-block mb-5">
                                                    <img src="<?php echo site_url('assets/images/logo.png'); ?>" alt="app-logo" height="18" />
                                                </a>
                                            </div> -->
                                            <h1 class="h5 mb-1">¡Bienvenid@!</h1>
                                            <p class="text-muted mb-4">Introduce tu dirección de email y tu contraseña para acceder al panel de administración.</p>
                                            <form method="post" id="form-login" class="user">
                                                <div class="form-group">
                                                    <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email">
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" class="form-control form-control-user" id="pass" name="pass" placeholder="Contraseña">
                                                </div>
                                                <div id="msj" class="card-title"><?php if(!empty($error))   echo $error; ?></div>
                                                <button id="boton-acceder" class="btn btn-success btn-block" style="background:#222222; border-color: #222222;"> Acceder </button>

                                            </form>

                                            <div class="row mt-4">
                                                <div class="col-12 text-center">
                                                    <p class="text-muted mb-2"><a href="<?php echo site_url('admin/recuperar-contrasena'); ?>" class="text-muted font-weight-medium ml-1">¿Olvidaste tu contraseña?</a></p>
                                                    <!--<p class="text-muted mb-0">Don't have an account? <a href="auth-register.html" class="text-muted font-weight-medium ml-1"><b>Sign Up</b></a></p>-->
                                                </div> <!-- end col -->
                                            </div>
                                            <!-- end row -->
                                        </div> <!-- end .padding-5 -->
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div> <!-- end .w-100 -->
                        </div> <!-- end .d-flex -->
                    </div> <!-- end col-->
                </div> <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

          <script>
            jQuery(function($) {
                $(document).ready(function(){
                    $('#boton-acceder').click(function(e){
                        $('#msj').html('');
                        if($('#email').val()==''||$('#pass').val()==''){
                            $('#msj').html('Debes introducir tu email y contraseña.');
                            return false;
                        }
                        $('#form-login').submit();
                        return true;
                    });
                });
            }); 
                
        </script>
    </body>

</html>
