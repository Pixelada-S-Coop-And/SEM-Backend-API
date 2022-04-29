<h1 class="display-5">Editar notificación</h1>
<hr />
<?php $back = $this->session->userdata('messages_back_link');  ?>
<a href="<?php if(empty($back)){  echo site_url('admin/messages'); }else{ echo $back; } ?>" style="color:#fff;" class="btn btn-primary d-none d-lg-block ml-2">
    <i class="feather-arrow-left"></i> Volver al listado
</a>
<?php $msg = $this->session->flashdata('action_ok');  

if(!empty($msg)) echo '<br /><br /><div class="alert alert-info">'.$msg.'</div>';
?>
<hr />
<div id="datos-message">
    
    <div class="row"> 
        <div class="col-lg-4 col-md-12">  
            <form id="form-message" method="post" enctype="multipart/form-data" action="">   
                <div id="field-active" class="form-group">
                    <label>Activo</label> &nbsp; <input type="checkbox" <?php if($message['active']==1): echo 'checked'; endif; ?> id="active" name="active" data-toggle="switchery" data-color="#222222"/> &nbsp; 
                <?php if($role==0): ?>
                &nbsp; <label>Global</label> &nbsp; <input type="checkbox" <?php if($message['global']==1): echo 'checked'; endif; ?> id="global" name="global" data-toggle="switchery" data-color="#222222"/>
                <?php endif; ?>
                </div>
                
                <div id="sections-field" class="form-group">
                    
                    <div class="mt-3">
                        <label>Secciones: </label> <br />
                        <?php if(!empty($sections)): 
                            foreach($sections as $section): ?>
                        <div class="custom-control custom-checkbox custom-control-inline" <?php if($role!=0&&!in_array($section['id'], $user_sections)): ?>style="opacity: 0.5;"<?php endif; ?>>
                            <input type="checkbox" <?php if($role!=0&&!in_array($section['id'], $user_sections)): ?>onclick="return false;"<?php endif; ?> class="custom-control-input section-check" name="sections[]" <?php if(!empty($message['sections'])&&in_array($section['id'], $message['sections'])){ echo 'checked'; } ?> id="section-<?php echo $section['id']; ?>" value="<?php echo $section['id']; ?>">
                            <label class="custom-control-label" for="section-<?php echo $section['id']; ?>"><?php echo $section['name']; ?></label>
                        </div>
                        <?php endforeach;
                        
                        endif; ?>
                    </div>
                </div>
                <?php if(intval($message['time']) <= time()): ?>  <p class="form-comment"><strong>NOTA: Las fechas no se puede editar una vez ha sido publicada la notificación</strong> </p> <?php  endif; ?> 
 
                <div class="form-group">
                    <label for="time">Fecha y hora de publicación *</label>
                    <input type="text" id="time" name="time" class="datetime-input form-control required   <?php if(intval($message['time']) <= time()): ?> noeditable <?php  endif; ?> " data-provide="datetimepicker"  <?php if(intval($message['time']) <= time()): ?> readonly <?php  endif; ?> value="<?php echo date('d/m/Y H:i:s', intval($message['time'])); ?>">
                </div>

                <div class="form-group">
                    <label for="expiration_time">Fecha y hora de caducidad</label>
                    <input type="text" id="expiration_time" name="expiration_time" class="datetime-input form-control   <?php if(intval($message['time']) <= time()): ?>  noeditable <?php  endif; ?> " data-provide="datetimepicker"   <?php if(intval($message['time']) <= time()): ?> readonly <?php  endif; ?>  value="<?php echo date('d/m/Y H:i:s', intval($message['expiration_time'])); ?>">
                </div>

                
                <div id="field-subject" class="form-group">
                    <label for="subject">Asunto *</label>
                    <input type="text" id="subject" name="subject" class="form-control required" value="<?php echo $message['subject']; ?>" placeholder=""/>
                </div>

                <div id="field-content" class="form-group">
                    <label for="content">Contenido</label>
                    <textarea id="content" name="content" class="form-control"><?php echo $message['content']; ?></textarea>
                </div>

                <div id="field-blog_post_title" class="form-group">
                    <label for="blog_post_title">Título de la noticia enlazada</label>
                    <input type="text" id="blog_post_title" name="blog_post_title" class="form-control" value="<?php echo $message['blog_post_title']; ?>" placeholder=""/>
                </div>

                <div id="field-blog_post_url" class="form-group">
                    <label for="blog_post_url">Enlace a noticia</label>
                    <input type="text" id="blog_post_url" name="blog_post_url" class="form-control" value="<?php echo $message['blog_post_url']; ?>" placeholder=""/>
                </div>
               
               <p>* Campos obligatorios</p>
                <div id="msj" class="card-title"></div>
                <button id="boton-guardar" type="button" class="btn btn-primary mb-2">Guardar</button>
            </form>
        </div>

        <div class="col-lg-4 col-md-12"> 
            <div class="card card-body">
                <h4 class="card-title">Adjuntos</h4>
                
                <table class="table table-borderless table-centered mb-0">
                    
                    <tbody>
                        <?php 
                        if(!empty($message['attachments'])): 
                            foreach($message['attachments'] as $item):
                                $info = pathinfo($item['url']);
                                $icon_class = 'far fa-file';
                                if(!empty($info['extension'])):
                                    switch ($info['extension']):
                                        case 'pdf':
                                            $icon_class = 'far fa-file-pdf'; break;
                                        case 'odt': case 'doc': case 'docx': case 'docm': case 'rtf':
                                            $icon_class = 'far fa-file-alt'; break;
                                        case 'ods': case 'xls': case 'xlsx': case 'xlsm':
                                            $icon_class = 'far fa-file-excel'; break;
                                        case 'csv':
                                            $icon_class = 'fas fa-file-csv'; break;
                                        case 'zip': case 'rar': case 'tar': case 'gz': case 'gzip':
                                            $icon_class = 'fas fa-archive'; break;
                                        case 'odp': case 'ppt': case 'pps': case 'ppsx':
                                            $icon_class = 'far fa-file-powerpoint'; break;
                                        case 'jpg': case 'jpeg': case 'png':  case 'pneg':  case 'gif':  case 'webp':  case 'bmp':
                                            $icon_class = 'far fa-images'; break;
                                        case 'txt': case 'json': default:
                                            $icon_class = 'far fa-file'; break;
                                    endswitch;
                                else:
                                    $icon_class = 'far fa-file'; 
                                endif;
                                    
                        ?>
                        <tr>
                            <td><a href="<?php echo $item['url'];?>" target="_blank" download><i class="<?php echo $icon_class; ?>" style="font-size: 30px; margin-right:10px;"></i> <?php echo $item['title']; ?></a></td>
                            <td>
                                <a class="link-eliminar" onclick="return confirm('¿Deseas eliminar este adjunto?')" href="<?php echo site_url('admin/messages/delete-attachment/'.$item['id']); ?>"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <?php endforeach;
                        else: ?>
                        <tr>
                            <td colspan="2">No se encuentran adjuntos</td>
                            
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card card-body">
                <form id="form-attachment" method="post" enctype="multipart/form-data" action="<?php echo site_url('admin/messages/add-attachment/'.$message['id']); ?>">   
                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>"/>
                    <h4 class="card-title">Añadir adjunto</h4>
                        <div id="field-attachment-title" class="form-group">
                            <label for="attachment-title">Título *</label>
                            <input type="text" id="attachment-title" name="title" class="form-control required" value="" placeholder=""/>
                        </div>
                        <div id="field-attachment-type" class="form-group">
                            <label>URL externa</label> &nbsp; <input type="checkbox"  id="attachment-type" name="attachment-type" value="1" data-toggle="switchery" data-color="#222222"/> &nbsp; 
                       
                        </div>
                        <div id="field-attachment-url" class="form-group">
                            <label for="attachment-url">Enlace a archivo</label>
                            <input type="text" id="attachment-url" name="attachment-url" class="form-control required" value="" placeholder=""/>
                        </div>
                        <div id="field-attachment-file" class="form-group">
                            <label for="attachment-file">Archivo *</label>
                            <input type="file" name="file" class="dropify required" data-max-file-size="8M" data-allowed-file-extensions="pdf odt doc docx docm rtf ods xls xlsx xlsm csv ppt pps ppsx txt json zip rar jpg jpeg png pneg webp gif bmp" />
                        </div>
                    <div id="msj2" class="card-title"></div>
                    <button id="add-button" type="button" class="btn btn-primary mb-2">Añadir</button>
                </form>
            </div>

            
        </div>
            
        </div>
        
    
</div>


<script>
$(document).ready(function() {

    $('input.datetime-input').datetimepicker({
        format: 'DD/MM/YYYY HH:mm:ss',sideBySide: true,
    });

    $('.input_number').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    if($('input#global').prop('checked')){
        $('#sections-field').hide();
    }else{
        $('#sections-field').show();
    }

    $('input#global').change(function(){
        if($(this).prop('checked')){
            $('#sections-field').hide();
        }else{
            $('#sections-field').show();
        }
    })
    if($('#attachment-type').prop('checked')){
        $('#field-attachment-file').hide();
        $('#field-attachment-url').show();
    }else{
        $('#field-attachment-file').show();
        $('#field-attachment-url').hide();
    }
    $('#attachment-type').change(function(){
        if($(this).prop('checked')){
            $('#field-attachment-file').hide();
            $('#field-attachment-url').show();
        }else{
            $('#field-attachment-file').show();
            $('#field-attachment-url').hide();
        }
    });
    $('#field-attachment-type span').click(function(){
        if( $('#attachment-type').prop('checked')){
            $('#field-attachment-file').hide();
            $('#field-attachment-url').show();
        }else{
            $('#field-attachment-file').show();
            $('#field-attachment-url').hide();
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

        if($('#msj').html()!=''){ 
            e.preventDefault();
            return false;
        } 
        $('#form-message').submit();
        return true;
    });

    $('#add-button').click(function(e){ 
        $('#msj2').html('');
        
        if($('#attachment-title').val()==''){
            $('#msj2').html('Debes rellenar los campos obligatorios');
            return false;
        }


        if($('#attachment-type').prop('checked')){
            if($('#attachment-url').val()==''){
                $('#msj2').html('Debes rellenar los campos obligatorios');
                return false;
            }
        }else{
            if($('#attachment-file').val()==''){
                $('#msj2').html('Debes rellenar los campos obligatorios');
                return false;
            }
        }
       
       

        if($('#msj2').html()!=''){ 
            e.preventDefault();
            return false;
        } 
        $('#form-attachment').submit();
        return true;
    });
   
});

</script>