<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Recuperar Contraseña | SEM</title>
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
                                            <p class="text-muted mb-4">Introduce tu nueva contraseña.</p>
                                            <form method="post" id="form-login" action="<?php echo site_url('cuenta/recuperar-contrasena/?token='.$token); ?>">
                                                <div class="form-group">
                                                    <input type="password" class="form-control form-control-user" id="pass" name="pass" placeholder="Contraseña">
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" class="form-control form-control-user" id="pass2" name="pass2" placeholder="Repetir contraseña">
                                                </div>
                                                <div id="msj" class="card-title"><?php if(!empty($error))   echo $error; ?></div>
                                                <button id="boton-acceder" class="btn btn-success btn-block"> Cambiar contraseña </button>
                                                
                                            </form>

                                            
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
                        if($('#pass').val()==''||$('#pass2').val()==''){
                            $('#msj').html('Debes introducir la contraseña dos veces.');
                            return false;
                        }
                        if($('#pass').val().length<6){
                            $('#msj').html('Las contraseña debe tener al menos 6 caracteres.');
                            return false;
                        }
                        if($('#pass').val()!=$('#pass2').val()){
                            $('#msj').html('Las contraseñas no coinciden.');
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