<h1 class="display-5">Nueva notificación</h1>
<hr />
<?php $back = $this->session->userdata('messages_back_link');  ?>
<a href="<?php if(empty($back)){  echo site_url('admin/messages'); }else{ echo $back; } ?>" style="color:#fff;" class="btn btn-primary d-none d-lg-block ml-2">
    <i class="feather-arrow-left"></i> Volver al listado
</a>
<hr />
<div id="datos-message">
    
    <div class="row"> 
        <div class="col-lg-4 col-md-12">  
            <form id="form-message" method="post" enctype="multipart/form-data" action="">   
                <div id="field-active" class="form-group">
                    <input type="hidden" name="active" value="0" />
                    <label>Activo</label> &nbsp; <input type="checkbox" id="active" checked name="active" data-toggle="switchery" data-color="#222222"/> &nbsp; 
                    <?php if($role==0): ?>&nbsp; <label>Global</label> &nbsp; <input type="checkbox" checked id="global" name="global" data-toggle="switchery" data-color="#222222"/><?php endif; ?>  &nbsp; 
                    <label>Publicar ahora</label> &nbsp; <input type="checkbox" id="now" checked name="now" data-toggle="switchery" data-color="#222222"/> &nbsp; 
                </div>
                
                <div id="sections-field" class="form-group">
                    
                    <div class="mt-3">
                        <label>Secciones: </label> <br />
                        <?php if(!empty($sections)): 
                            foreach($sections as $section): ?>
                        <div class="custom-control custom-checkbox custom-control-inline" <?php if($role!=0&&!in_array($section['id'], $user_sections)): ?>style="opacity: 0.5;"<?php endif; ?>>
                            <input type="checkbox" <?php if($role!=0&&!in_array($section['id'], $user_sections)): ?>onclick="return false;"<?php endif; ?> class="custom-control-input section-check" name="sections[]" id="section-<?php echo $section['id']; ?>" value="<?php echo $section['id']; ?>">
                            <label class="custom-control-label" for="section-<?php echo $section['id']; ?>"><?php echo $section['name']; ?></label>
                        </div>
                        <?php endforeach;
                        
                        endif; ?>
                    </div>
                </div>
                
                <div class="form-group datetime-field-group">
                    <label for="time">Fecha y hora de publicación *</label>
                    <input type="text" id="time" name="time" class="datetime-input form-control" data-provide="datetimepicker" value="">
                </div>

                <div class="form-group">
                    <label for="expiration_time">Fecha y hora de caducidad</label>
                    <input type="text" id="expiration_time" name="expiration_time" class="datetime-input form-control" data-provide="datetimepicker" value="">
                </div>

                <div id="field-subject" class="form-group">
                    <label for="subject">Asunto *</label>
                    <input type="text" id="subject" name="subject" class="form-control required" value="" placeholder=""/>
                </div>

                <div id="field-content" class="form-group">
                    <label for="content">Contenido</label>
                    <textarea id="content" name="content" class="form-control"></textarea>
                </div>

                <div id="field-blog_post_title" class="form-group">
                    <label for="blog_post_title">Título de la noticia enlazada</label>
                    <input type="text" id="blog_post_title" name="blog_post_title" class="form-control" value="" placeholder=""/>
                </div>

                <div id="field-blog_post_url" class="form-group">
                    <label for="blog_post_url">Enlace a noticia</label>
                    <input type="text" id="blog_post_url" name="blog_post_url" class="form-control" value="" placeholder=""/>
                </div>
               
               <p>* Campos obligatorios</p>
                <div id="msj" class="card-title"></div>
                <button id="boton-guardar" type="button" class="btn btn-primary mb-2">Siguiente</button>
            </form>
        </div>
    
            
        </div>
        
    
</div>


<script>
jQuery(function($) {
    $('.datetime-input').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',sideBySide: true,
    });

    $('.input_number').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    if($('input#global').prop('checked')){
        $('#sections-field').hide();
    }else{
        $('#sections-field').show();
    }
    
    if($('input#now').prop('checked')){
        $('.datetime-field-group').hide();
    }else{
        $('.datetime-field-group').show();
    }

    $('input#global').change(function(){
        if($(this).prop('checked')){
            $('#sections-field').hide();
        }else{
            $('#sections-field').show();
        }
    });

    $('input#now').change(function(){
        if($(this).prop('checked')){
            $('.datetime-field-group').hide();
        }else{
            $('.datetime-field-group').show();
        }
    });

    


 
    $('#boton-guardar').click(function(e){  
        $('#msj').html('');
        
        $('#form-message .form-group .required').each(function(){
            if($(this).val()==''){
                $('#msj').html('Debes rellenar los campos obligatorios');
                return false;
            }
        });

        if(!$('input#global').prop('checked')&&$('#sections-field input:checked').length==0){
            $('#msj').html('Debes seleccionar al menos una sección');
            return false;
        }

        if(!$('input#now').prop('checked')&&$('#time').val()==''){
            $('#msj').html('Debes indicar fecha y hora');
            return false;
        }

        if($('#msj').html()!=''){ 
            e.preventDefault();
            return false;
        } 
        $('#form-message').submit();
        return true;
    });

 
   
});

</script>