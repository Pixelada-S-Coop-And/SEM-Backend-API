<h1 class="display-5">Nueva sección</h1>
<hr />
<?php $back = $this->session->userdata('sections_back_link');  ?>
<a href="<?php if(empty($back)){  echo site_url('admin/sections'); }else{ echo $back; } ?>>" style="color:#fff;" class="btn btn-primary d-none d-lg-block ml-2">
    <i class="feather-arrow-left"></i> Volver al listado
</a>
<hr />
<div id="datos-section">
    <form id="form-section" method="post" enctype="multipart/form-data" action=""> 
        <div class="row"> 
            <div class="col-lg-4 col-md-12">    
                
                <div id="field-name" class="form-group">
                    <label for="name">Nombre *</label>
                    <input type="text" id="name" name="name" class="form-control required" value="" placeholder=""/>
                </div>

                <div id="field-description" class="form-group">
                    <label for="description">Descripción</label>
                    <textarea id="description" name="description" class="form-control"></textarea>
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
   
 
    $('#boton-guardar').click(function(e){ 
        $('#msj').html('');
        
       
        $('#form-section .form-group .required').each(function(){
            if($(this).val()==''){
                $('#msj').html('Debes rellenar los campos obligatorios');
                return false;
            }
        });

        if($('#msj').html()!=''){ 
            e.preventDefault();
            return false;
        } 
        $('#form-section').submit();
        return true;
    });
   
});

</script>