<h1 class="display-5">Nuevo usuario</h1>
<hr />
<?php $back = $this->session->userdata('users_back_link');  ?>
<a href="<?php if(empty($back)){  echo site_url('admin/users'); }else{ echo $back; } ?>" style="color:#fff;" class="btn btn-primary d-none d-lg-block ml-2">
    <i class="feather-arrow-left"></i> Volver al listado
</a>
<hr />
<div id="datos-usuario">
    <form id="form-usuario" method="post" enctype="multipart/form-data" action=""> 
        <div class="row"> 
            <div class="col-md-6">    
                <div id="field-active" class="form-group">
                    <label>Activo</label><br />
                    <input type="checkbox" checked id="active" name="active" data-toggle="switchery" data-color="#222222"/>
                </div>
                <div id="field-role" class="form-group">
                    <h6 class="mt-4">Tipo de usuario</h6>
                    <div class="mt-3">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="role-manager" name="role" class="custom-control-input" value="1" <?php echo 'checked'; ?> />
                            <label class="custom-control-label" for="role-manager">Gestor de sección</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="role-administrator" name="role" class="custom-control-input" value="0" />
                            <label class="custom-control-label" for="role-administrator" >Administrador</label>
                        </div>
                    </div>
                </div>
                <div id="sections-field" class="form-group">
                    
                    <div class="mt-3">
                        <label>Secciones: </label> <br />
                        <?php if(!empty($sections)): 
                            foreach($sections as $section): ?>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input section-check" name="sections[]" id="section-<?php echo $section['id']; ?>" value="<?php echo $section['id']; ?>">
                            <label class="custom-control-label" for="section-<?php echo $section['id']; ?>"><?php echo $section['name']; ?></label>
                        </div>
                        <?php endforeach;
                        
                        endif; ?>
                    </div>
                </div>
                <div id="field-solucion" class="form-group">
                    <label for="simpleinput">Nombre *</label>
                    <input type="text" id="name" name="name" class="form-control required" value="" placeholder=""/>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" class="form-control required" name="email" id="email" placeholder="">
                </div>
                <div class="form-group">
                    <label for="pass">Contraseña *</label>
                    <input type="password" id="pass" name="pass" class="form-control required" value="">
                </div>
                <div class="form-group">
                    <label for="pass2">Repetir contraseña *</label>
                    <input type="password" id="pass2" class="form-control required" value="">
                </div>
                <p>* Campos obligatorios</p>
                <div id="msj" class="card-title"></div>
            </div>
            
        </div>
        
        <button id="boton-guardar" type="button" class="btn btn-primary mb-2">Crear usuario</button>
    </form>
</div>


<script>
jQuery(function($) {
    if($('#role-manager').prop('checked')){
        $('#sections-field').show();
    }else{
        $('#sections-field').hide();
    }

    $('#field-role input').change(function(){
        if($('#role-manager').prop('checked')){
            $('#sections-field').show();
        }else{
            $('#sections-field').hide();
        }
    });
    $('#boton-guardar').click(function(e){ 
        $('#msj').html(''); 
       
        $('#form-usuario .form-group .required').each(function(){
            if($(this).val()==''){
                $('#msj').html('Debes rellenar los campos obligatorios');
                return false;
            }
        });

        

        if(!validar_email($('#email').val())){
            $('#msj').html('El email introducido no es correcto');
            return false;
        }

        var emails = [<?php foreach($users as $u): echo '"'.$u['email'].'",'; endforeach?>];

        for(var i=0;i<emails.length;i++){
            if($('#email').val()==emails[i]){
                $('#msj').html('El email introducido ya fue utilizado por otro usuario');
                return false;
            }
        }

        if($('#role-manager').prop('checked')&&$('#sections-field input:checked').length==0){ 
            $('#msj').html('Debes seleccionar al menos una sección');
            return false;
        }

        if($('#pass').val().length<6){
            $('#msj').html('La contraseña debe tener al menos 6 caracteres.');
            return false;
        }

        if($('#pass').val()!=$('#pass2').val()){
            $('#msj').html('Las contraseñas no coinciden');
            return false;
        }

        if($('#msj').html()!=''){ 
            e.preventDefault();
            return false;
        } 
        $('#form-usuario').submit();
        return true;
    });
   
});

</script>

                                