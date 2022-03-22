<h1 class="display-5">Configuración</h1>
<?php $msg = $this->session->flashdata('action_ok');  

if(!empty($msg)) echo '<br /><br /><div class="alert alert-info">'.$msg.'</div>';
?>
<hr />

<div id="settings-wrap" class="row">
    
<?php //print_array($users); ?>
    <div class="col-md-12">
        <form id="form-settings" method="post" enctype="multipart/form-data" action=""> 
            <button id="boton-guardar" type="submit" class="btn btn-primary mb-2">Guardar cambios</button><br /><hr />
            <div class="card card-body">
                <h4 class="card-title">Token de seguridad</h4>

                <div id="field-token" class="form-group">
                    <label for="token">Token de seguridad</label>
                    <input type="text" id="token" name="token" class="form-control" value="<?php echo $option_model->option('token', ''); ?>" placeholder=""/>
                </div>
            </div>
            <hr />
            <div class="card card-body">
                <h4 class="card-title">Configuración Email</h4>
                
                <div id="smtp-fields">

                    <div id="field-smtp_host" class="form-group">
                        <label for="smtp_host">Servidor SMTP</label>
                        <input type="text" id="smtp_host" name="smtp_host" class="form-control" value="<?php echo $option_model->option('smtp_host', ''); ?>" placeholder=""/>
                    </div>

                    <div id="field-smtp_user" class="form-group">
                        <label for="smtp_user">Usuario SMTP</label>
                        <input type="text" id="smtp_user" name="smtp_user" class="form-control" value="<?php echo $option_model->option('smtp_user', ''); ?>" placeholder=""/>
                    </div>

                    <div id="field-smtp_pass" class="form-group">
                        <label for="smtp_pass">Contraseña SMTP</label>
                        <input type="password" id="smtp_pass" name="smtp_pass" class="form-control" value="<?php echo $option_model->option('smtp_pass', ''); ?>" placeholder=""/>
                    </div>

                    <div id="field-smtp_port" class="form-group">
                        <label for="smtp_port">Puerto SMTP</label>
                        <input type="text" id="smtp_port" name="smtp_port" class="form-control" value="<?php echo $option_model->option('smtp_port', ''); ?>" placeholder=""/>
                    </div>

                    <div id="field-smtp_crypto" class="form-group">
                        <label for="smtp_pass">Seguridad SMTP</label>
                        <input type="text" id="smtp_crypto" name="smtp_crypto" class="form-control" value="<?php echo $option_model->option('smtp_crypto', ''); ?>" placeholder=""/>
                    </div>

                    <div id="field-smtp_pass" class="form-group">
                        <label for="smtp_pass">Codificación Email</label>
                        <input type="text" id="smtp_charset" name="smtp_charset" class="form-control" value="<?php echo $option_model->option('smtp_charset', ''); ?>" placeholder=""/>
                    </div>

                    <div id="field-smtp_from" class="form-group">
                        <label for="smtp_from">Email de origen</label>
                        <input type="text" id="smtp_from" name="smtp_from" class="form-control" value="<?php echo $option_model->option('smtp_from', ''); ?>" placeholder=""/>
                    </div>
                    <div id="field-form_email" class="form-group">
                        <label for="form_email">Email de destino del formulario</label>
                        <input type="text" id="form_email" name="form_email" class="form-control" value="<?php echo $option_model->option('form_email', ''); ?>" placeholder=""/>
                    </div>
                </div>
            </div>
            <?php //print_array($options); ?>
        </form>
    </div>
    
</div>