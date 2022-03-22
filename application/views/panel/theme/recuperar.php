<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Recuperar Contraseña | SEM Administración</title>
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
                                            <h1 class="h5 mb-1">Recuperar Contraseña</h1>
                                            <p class="text-muted mb-4">Introduce tu dirección de email y te enviaremos un email con instrucciones para recuperar tu contraseña.</p>
                                            <form method="post" id="form-login">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail">Email</label>
                                                    <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email">
                                                </div>
                                                <div id="msj" class="card-title"><?php if(!empty($error))   echo $error; ?></div>
                                                <button id="boton-acceder" class="btn btn-success btn-block"> Enviar </button>
                                                
                                            </form>

                                            <div class="row mt-5">
                                                <div class="col-12 text-center">
                                                    <p class="text-muted">¿Tienes una cuenta?  <a href="<?php echo site_url('admin/login'); ?>" class="text-muted font-weight-medium ml-1"><b>Accede</b></a></p>
<!--                                                    <p class="text-muted mb-0">Don't have an account? <a href="auth-register.html" class="text-muted font-weight-medium ml-1"><b>Sign Up</b></a></p>-->
                                                </div> <!-- end col -->
                                            </div>
                                            <!-- end row -->
                                        </div>
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
                        if($('#email').val()==''){
                            $('#msj').html('Debes introducir tu email.');
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