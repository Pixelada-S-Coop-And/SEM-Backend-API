<h1 class="display-5">Afiliados/as</h1>
<hr />
<a id="boton-nuevo" href="<?php echo site_url('admin/affiliates/new'); ?>" style="color:#fff;" class="btn btn-primary d-none d-lg-block ml-2">
    Nuevo afiliado
</a>
<?php $msg = $this->session->flashdata('action_ok');  
    if(!empty($msg)) echo '<br /><br /><div class="alert alert-info">'.$msg.'</div>';
?>
<div id="list-filter">
    <form id="form-filter" method="get" enctype="multipart/form-data" action=""> 
    <div id="filter-section_id-group" class="form-group">
        <label for="name">Filtrar por sección</label>
        <select id="filter-section_id" name="section_id" class="form-control">
            <?php if($role==0): ?><option value="">Todas las secciones</option><?php endif; ?>
            <?php 
            if(!empty($sections)&&is_array($sections)):
                foreach($sections as $section):
                    if($role==0||$section['id']==$current_user_section_id):
                        echo '<option value="'.$section['id'].'"';
                        if(!empty($_GET['section_id'])&&intval($section['id'])==intval($_GET['section_id'])) echo 'selected';
                        echo '>'.$section['name'].'</option>';
                    endif;
                endforeach;
            endif;
            ?>
        </select>
    </div>
        </form>
</div>
<div id="lista-affiliates" class="row">
<?php //print_array($affiliates); ?>
<div class="col-xl-10 col-lg-12 col-12">
    <table id="tabla-affiliates" class="table dt-responsive nowrap">
        <thead>
            <tr>
                <th id="col-0" data-priority="1">ID</th>
                <th id="col-1" data-priority="1">Nº Afiliado/a</th>
                <th id="col-2" data-priority="1">Nombre</th>
                <th id="col-3" data-priority="3">DNI</th>
                <th id="col-4" data-priority="3">Sección sindical</th>
                <th id="col-5" data-priority="3">Fecha de afiliación</th>
                <th id="col-6" data-priority="3">Activo</th>
                <th id="col-7" data-priority="1"></th>
            </tr>
        </thead>
    
    
        <tbody>
        <?php $i=0; if(!empty($affiliates)): 
            foreach($affiliates as $affiliate): $i++; ?>
            <tr>
                <td data-priority="1"><?php echo $affiliate['id']; ?></td>
                <td data-priority="1"><?php echo $affiliate['number']; ?></td>
                <td data-priority="1"><a href="<?php echo site_url('admin/affiliates/edit/'.$affiliate['id']); ?>"><strong><?php echo $affiliate['name'].' '.$affiliate['surnames']; ?></strong></a></td>
                <td data-priority="3"><?php echo $affiliate['id_card']; ?></td>
                <td data-priority="3"><?php echo $affiliate['section_name']; ?></td>
                <td data-priority="3"><span class="hidden-text"><?php echo $affiliate['affiliation_date']; ?></span><?php echo date('d/m/Y', strtotime($affiliate['affiliation_date'])); ?></td>
                <td data-priority="3"><?php echo (empty($affiliate['active']))? 'Inactivo' : 'Activo';  ?></td>
                <td data-priority="1" style="text-align: right;">
                    <?php if($affiliate['has_session']): ?><a href="<?php echo site_url('admin/affiliates/logout/'.$affiliate['id']).'?'.$_SERVER['QUERY_STRING']; ?>" class="btn btn-primary d-none d-lg-block ml-2">Cerrar sesión</a><?php endif; ?> &nbsp; 
                    <a href="<?php echo site_url('admin/affiliates/send-pass/'.$affiliate['id']).'?'.$_SERVER['QUERY_STRING']; ?>" class="btn btn-primary d-none d-lg-block ml-2">Enviar clave</a> &nbsp; 
                    <a class="link-editar" href="<?php echo site_url('admin/affiliates/edit/'.$affiliate['id']); ?>"><i class="fas fa-edit"></i></a> &nbsp; 
                    <a class="link-eliminar" onclick="return confirm('¿Deseas eliminar este afiliado?')" href="<?php echo site_url('admin/affiliates/delete/'.$affiliate['id']); ?>"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
        <?php endforeach;  else: echo '<tr><td colspan="7">No se encuentran afiliados/as</td></tr>'; 
        endif;?>
        </tbody>
    </table>
</div>
</div>

<script>

<?php if($i>0): ?>
var table = $('#tabla-affiliates').DataTable({
        "language": {
            "paginate": {
                "previous": "<",
                "next": ">"
            }
        },
        "scrollX": true,
        "autoWidth": false,


    <?php if(isset($_GET['orderby'])): ?>
            "order": [[ <?php echo intval($_GET['orderby']); ?>, "<?php if(!empty($_GET['order'])&&$_GET['order']=='desc'): echo $_GET['order']; else: echo 'asc'; endif; ?>" ]],
    <?php endif;  ?>

        "pageLength" : 25,
        "drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
        }
    });

    <?php $url_base = site_url('/admin/affiliates'); ?>
    <?php if(!empty($_GET['search'])): ?>
    $('#tabla-affiliates_filter input.form-control').val('<?php echo $_GET['search']; ?>');
    $('#tabla-affiliates_filter input.form-control').trigger('keyup');
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

        var n_page = parseInt($('.pagination .paginate_button.active a').html());
        if(parseInt(n_page)<1) n_page = 1; 

        if($(this).parent().hasClass('next')){
            n_page = n_page + 1;
        }else if($(this).parent().hasClass('previous')){
            n_page = n_page - 1;
            if(n_page<1) n_page = 1;
        }else{
            n_page = parseInt($(this).html());
        }
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
        ?>page="+n_page;
    });

    $('#tabla-affiliates_length select').change(function(e){
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

    $('#tabla-affiliates_filter input.form-control').keyup(function(e){
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

