<h1 class="display-5">Añadir afiliado/a</h1>
<hr />
<?php $back = $this->session->userdata('affiliates_back_link');  ?>
<a href="<?php if(empty($back)){  echo site_url('admin/affiliates'); }else{ echo $back; } ?>" style="color:#fff;" class="btn btn-primary d-none d-lg-block ml-2">
    <i class="feather-arrow-left"></i> Volver al listado
</a>
<hr />
<div id="datos-affiliate">
    <form id="form-affiliate" method="post" enctype="multipart/form-data" action=""> 
        <div class="row"> 
            <div class="col-md-6">    
                
                <div id="field-active" class="form-group">
                    <label>Activo</label><br />
                    <input type="checkbox" checked id="active" name="active" data-toggle="switchery" data-color="#222222"/>
                </div>

                <div id="field-section_id" class="form-group">
                    <label for="section_id">Sección *</label>
                   
                    <select id="section_id" name="section_id" class="form-control required">
                        <option value="">Selecciona una sección</option>
                        <?php 
                        if(!empty($sections)&&is_array($sections)):
                            foreach($sections as $section):
                                echo '<option value="'.$section['id'].'"';
                                echo '>'.$section['name'].'</option>';
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                <!--
                <div id="field-number" class="form-group">
                    <label for="number">Nº de afiliado/a *</label>
                    <input type="text" id="number" name="number" class="form-control required" value="" placeholder=""/>
                </div>-->

                <div id="field-name" class="form-group">
                    <label for="name">Nombre *</label>
                    <input type="text" id="name" name="name" class="form-control required" value="" placeholder=""/>
                </div>

                <div id="field-surnames" class="form-group">
                    <label for="surnames">Apellidos *</label>
                    <input type="text" id="surnames" name="surnames" class="form-control required" value="" placeholder=""/>
                </div>

                <div class="form-group">
                    <label for="id_card">DNI *</label>
                    <input type="text" class="form-control required" name="id_card" id="id_card" placeholder="" value="">
                </div>

                <div class="form-group">
                    <label for="birthdate">Fecha de nacimiento *</label>
                    <input type="text" id="birthdate" name="birthdate" class="date-input form-control required" data-provide="datepicker" value="">
                </div>

                <div class="form-group">
                    <label for="affiliation_date">Fecha de afiliación *</label>
                    <input type="text" id="affiliation_date" name="affiliation_date" class="date-input form-control required" data-provide="datepicker" value="">
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" class="form-control required" name="email" id="email" placeholder="" value="">
                </div>

                <div class="form-group">
                    <label for="phone">Teléfono *</label>
                    <input type="text" class="form-control input_number required" name="phone" id="phone" placeholder="" value="">
                </div>

                <div class="form-group">
                    <label for="job">Trabajo</label>
                    <input type="text" class="form-control" name="job" id="job" placeholder="" value="">
                </div>

                <div class="form-group">
                    <label for="job_position">Puesto de trabajo</label>
                    <input type="text" class="form-control" name="job_position" id="job_position" placeholder="" value="">
                </div>

                <div class="form-group">
                    <label for="address">Dirección</label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="" value="">
                </div>

                <div class="form-group">
                    <label for="address">Código Postal</label>
                    <input type="text" class="form-control input_number" name="zipcode" id="zipcode" placeholder="" value="">
                </div>

                <div class="form-group">
                    <label for="address">Localidad</label>
                    <input type="text" class="form-control" name="location" id="location" placeholder="" value="">
                </div>
                
                <div id="field-province_id" class="form-group">
                    <label for="province_id">Provincia</label>
                   
                    <select id="province_id" name="province_id" class="form-control">
                        <option value="">Selecciona una provincia</option>
                        <?php 
                        if(!empty($provinces)&&is_array($provinces)):
                            foreach($provinces as $province):
                                echo '<option value="'.$province['id'].'"';
                                echo '>'.$province['name'].'</option>';
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                
               
                <p>* Campos obligatorios</p>
                <div id="msj" class="card-title"></div>
            </div>
            
        </div>
        
        <button id="boton-guardar" type="button" class="btn btn-primary mb-2">Guardar</button>
    </form>
</div>


<script>
jQuery(function($) {
   
    $('.date-input').datepicker({
        format: 'yyyy-mm-dd',sideBySide: true, language: 'es', weekStart: 1
    });

    $('.input_number').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $('#boton-guardar').click(function(e){ 
        $('#msj').html('');
        
       
        $('#form-affiliate .form-group .required').each(function(){
            if($(this).val()==''){
                $('#msj').html('Debes rellenar los campos obligatorios');
                return false;
            }
        });

        if(!validar_email($('#email').val())){
            $('#msj').html('El email introducido no es correcto');
            return false;
        }

        var emails = [<?php foreach($affiliates as $u): echo '"'.$u['email'].'",';  endforeach; ?>];

        for(var i=0;i<emails.length;i++){
            if($('#email').val()==emails[i]){
                $('#msj').html('El email introducido ya fue utilizado por otro afiliado');
                return false;
            }
        }

        

        if($('#msj').html()!=''){ 
            e.preventDefault();
            return false;
        } 
        $('#form-affiliate').submit();
        return true;
    });
   
});

</script>