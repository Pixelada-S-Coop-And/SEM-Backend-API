<h1 class="display-5">Notificaciones</h1>
<hr />
<a id="boton-nuevo" href="<?php echo site_url('admin/messages/new'); ?>" style="color:#fff;" class="btn btn-primary d-none d-lg-block ml-2">
    Nueva notificación
</a>
<?php $msg = $this->session->flashdata('action_ok');  

if(!empty($msg)) echo '<br /><br /><div class="alert alert-info">'.$msg.'</div>';
?>
<div id="list-filter">
    <form id="form-filter" method="get" enctype="multipart/form-data" action=""> 
    <div id="filter-section_id-group" class="form-group">
        <label for="name">Filtrar por sección</label>
        <select id="filter-section_id" name="section_id" class="form-control">
            <?php if($role==0): ?><option value="">Todas las secciones</option><?php else: ?><option value="">Todas mis secciones</option><?php endif; ?>
            <?php 
            if(!empty($sections)&&is_array($sections)):
                foreach($sections as $section):     
                        echo '<option value="'.$section['id'].'"';
                        if(!empty($_GET['section_id'])&&intval($section['id'])==intval($_GET['section_id'])) echo 'selected';
                        echo '>'.$section['name'].'</option>';
                endforeach;
            endif;
            ?>
        </select>
    </div>
        </form>
</div>
<div id="lista-messages" class="row">
<?php //print_array($messages); ?>
<div class="col-xl-12 col-lg-12 col-12">
    <table id="tabla-messages"  class="table dt-responsive nowrap">
        <thead>
            <tr>
                <th id="col-0">ID</th>
                <th id="col-1">Activa</th>
                <th id="col-2">Fecha de publicación</th>
                <th id="col-3">Fecha de expiración</th>
                <th id="col-4">Asunto</th>
                <th id="col-5">Destino</th>
                
                <th></th>
            </tr>
        </thead>
    
    
        <tbody>
        <?php $i=0; if(!empty($messages)): 
            foreach($messages as $message): $i++; ?>
            <tr>
                <td><?php echo $message['id']; ?></td>
                <td><?php echo (empty($message['active']))? 'Inactiva' : 'Activa';  ?></td>
                <td><span class="hidden-text"><?php echo date('Y-m-d H:i:s', intval($message['time'])); ?></span><?php echo date('d/m/Y H:i', intval($message['time'])); ?></td>
                <td><span class="hidden-text"><?php echo date('Y-m-d H:i:s', intval($message['expiration_time'])); ?></span><?php echo date('d/m/Y H:i', intval($message['expiration_time'])); ?></td>
                <td><a href="<?php echo site_url('admin/messages/edit/'.$message['id']); ?>"><strong><?php echo $message['subject']; ?></strong></a></td>
                <td><?php echo $message['target']; ?></td>
                <td style="text-align: right;">
                    <a class="link-editar" href="<?php echo site_url('admin/messages/edit/'.$message['id']); ?>"><i class="fas fa-edit"></i></a> &nbsp; 
                    <a class="link-eliminar" onclick="return confirm('¿Deseas eliminar esta notificación?')" href="<?php echo site_url('admin/messages/delete/'.$message['id']); ?>"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
        <?php endforeach;
        else: echo '<tr><td colspan="7">No se encuentran notificaciones</td></tr>'; 
        endif;?>
        </tbody>
    </table>
</div>
</div>

<script>
<?php if($i>0): ?>
var table = $('#tabla-messages').DataTable({
        "language": {
            "paginate": {
                "previous": "<",
                "next": ">"
            }
        },
        "scrollX": true,
        "autoWidth": false,
        "pageLength" : 25,
        <?php if(isset($_GET['orderby'])): ?>
"order": [[ <?php echo intval($_GET['orderby']); ?>, "<?php if(!empty($_GET['order'])&&$_GET['order']=='desc'): echo $_GET['order']; else: echo 'asc'; endif; ?>" ]],
    <?php else: ?>
"order": [[ 2, "desc" ]],
    <?php endif;  ?>
        "drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
        }
    });

    <?php $url_base = site_url('/admin/messages'); ?>
    <?php if(!empty($_GET['search'])): ?>
    $('#tabla-messages_filter input.form-control').val('<?php echo $_GET['search']; ?>');
    $('#tabla-messages_filter input.form-control').trigger('keyup');
    <?php endif ?>

    <?php if(!empty($_GET['p_size'])): ?>
    table.page.len(<?php echo intval($_GET['p_size']); ?>).draw();
    <?php endif; ?>

    <?php if(!empty($_GET['page'])): ?>
    table.page(<?php echo intval($_GET['page']) - 1; ?>).draw('page');
    <?php endif; ?> 
    $(document).ready(function(){



   
    
    $('#filter-section_id').change(function(){
        window.location.href="<?php echo $url_base; ?>?section_id="+$(this).val();
    });

    $('.pagination .paginate_button a').click(function(e){
        e.preventDefault();
        window.location.href="<?php echo $url_base; ?>?<?php 
        if(isset($_GET['page'])){ 
            unset($_GET['page']); 
        }

        $param = '';
        if(!empty($_GET)){
            foreach($_GET as $key => $val){
                if(!empty($param))  $param .= '&';
                $param.=$key.'='.urlencode($val);
            }
        }
            
        if(!empty($param)){ 
            $param.='&'; 
        } 
        echo $param; 
        ?>page="+$(this).attr('data-dt-idx');
    });

    $('#tabla-messages_length select').change(function(e){
        e.preventDefault();
        window.location.href="<?php echo $url_base; ?>?<?php 
        if(isset($_GET['page']))
            unset($_GET['page']); 
        if(isset($_GET['p_size']))
            unset($_GET['p_size']); 
            
        $param = '';
        if(!empty($_GET)){
            foreach($_GET as $key => $val){
                if(!empty($param))  $param .= '&';
                $param.=$key.'='.urlencode($val);
            }
        }
        if(!empty($param)){ 
            $param.='&'; 
        } 
        echo $param; 
        ?>p_size="+$(this).val();
    });

    $('#tabla-messages_filter input.form-control').keyup(function(e){
        e.preventDefault();
        var self = $(this);
        setTimeout(function(){
            if($.trim(self.val())!='')
                window.location.href="<?php echo $url_base; ?>?<?php 
        if(isset($_GET['page']))
            unset($_GET['page']); 
        if(isset($_GET['p_size']))
            unset($_GET['p_size']); 
        if(isset($_GET['search']))
            unset($_GET['search']); 
            
        $param = '';
        if(!empty($_GET)){
            foreach($_GET as $key => $val){
                if(!empty($param))  $param .= '&';
                $param.=$key.'='.urlencode($val);
            }
        } 
        if(!empty($param)){ 
            $param.='&'; 
        } 
        echo $param; 
        ?>search="+self.val();
            else    window.location.href="<?php echo $url_base; ?>";
        }, 2000);
    });

    $('table.table.dt-responsive thead tr th').click(function(e){
        e.preventDefault();
        if($(this).hasClass('sorting_desc')) var sorting = 'desc';
        else var sorting = 'asc';
        window.location.href="<?php echo $url_base; ?>?<?php 
        if(isset($_GET['orderby']))
            unset($_GET['orderby']); 
        if(isset($_GET['order']))
            unset($_GET['order']); 
            
        $param = '';
        if(!empty($_GET)){
            foreach($_GET as $key => $val){
                if(!empty($param))  $param .= '&';
                $param.=$key.'='.urlencode($val);
            }
        }
        if(!empty($param)){ 
            $param.='&'; 
        } 
        echo $param; 
        ?>orderby="+$(this).attr('id').replace('col-', '')+'&order='+sorting;
    });    

    });

    <?php endif; ?>
</script>
