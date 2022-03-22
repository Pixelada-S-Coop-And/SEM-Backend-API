<h1 class="display-5">Usuarios/as</h1>
<hr />
<a id="boton-nuevo" href="<?php echo site_url('admin/users/new'); ?>" style="color:#fff;" class="btn btn-primary d-none d-lg-block ml-2">
    Nuevo usuario
</a>
<?php $msg = $this->session->flashdata('action_ok');  

if(!empty($msg)) echo '<br /><br /><div class="alert alert-info">'.$msg.'</div>';
?>
<div id="lista-users" class="row">
<?php //print_array($users); ?>
<div class="col-md-6">
    <table id="tabla-users" class="table dt-responsive nowrap">
        <thead>
            <tr>
                <th id="col-0">ID</th>
                <th id="col-1">Nombre</th>
                <th id="col-2">Email</th>
                <th id="col-3">Nivel</th>
                <th id="col-4">Secciones sindicales</th>
                <th id="col-5">Activo</th>
                <th id="col-6"></th>
            </tr>
        </thead>
    
    
        <tbody>
        <?php $i=0; if(!empty($users)): foreach($users as $user): $i++; ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><a href="<?php echo site_url('admin/users/edit/'.$user['id']); ?>"><strong><?php echo $user['name']; ?></strong></a></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo ($user['role']==0)? '<strong>Administrador</strong>' : 'Gestor de sección';  ?></td>
                <td><?php echo $user['sections_string']; ?></td>
                <td><?php echo (empty($user['active']))? 'Inactivo' : 'Activo';  ?></td>
                <td>
                    <a class="link-editar" href="<?php echo site_url('admin/users/edit/'.$user['id']); ?>"><i class="fas fa-edit"></i></a>
                    <a class="link-eliminar" onclick="return confirm('¿Deseas eliminar este usuario?')" href="<?php echo site_url('admin/users/delete/'.$user['id']); ?>"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
        <?php endforeach;  else: echo '<tr><td colspan="7">No se encuentran usuarios</td></tr>'; 
        endif; ?>
        
        </tbody>
    </table>
</div>
</div>

<script>

<?php if($i>0): ?>
    var table = $('#tabla-users').DataTable({
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
        "pageLength" : 2500,
        "drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
        }
    });

    table.page.len(25).draw();

    <?php $url_base = site_url('/admin/users'); ?>
    <?php if(!empty($_GET['search'])): ?>
    $('#tabla-users_filter input.form-control').val('<?php echo $_GET['search']; ?>');
    $('#tabla-users_filter input.form-control').trigger('keyup');
    <?php endif ?>

    <?php if(!empty($_GET['p_size'])): ?>
    table.page.len(<?php echo intval($_GET['p_size']); ?>).draw();
    <?php endif; ?>

    <?php if(!empty($_GET['page'])): ?>
    table.page(<?php echo intval($_GET['page']) - 1; ?>).draw('page');
    <?php endif; ?> 
    $(document).ready(function(){



   
    
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

    
    $('#tabla-users_length select').change(function(e){
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

    $('#tabla-users_filter input.form-control').keyup(function(e){
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