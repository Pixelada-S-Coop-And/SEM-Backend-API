<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

<div data-simplebar class="h-100">

    <!-- LOGO -->
    <div class="navbar-brand-box" style="padding:0 0 20px;">
        <a href="<?php echo site_url('admin'); ?>" class="logo">
            <span>
                <img id="logo-menu" src="<?php echo site_url('assets/images/logo.png'); ?>" alt="" style="width:100%;height: auto;">
            </span>
            <i><img src="<?php echo site_url('assets/images/logo-small.png'); ?>" alt="" height="24"></i>
        </a>
    </div>

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title">Menu</li>
            <!--            <li class="<?php if($current=='home'){ echo 'mm-active'; } ?>">
                <a href="<?php echo site_url('admin'); ?>" class="<?php if($current=='home'){ echo 'active'; } ?>"><i class="feather-home"></i><span>Panel</span></a>
            </li>
-->         <?php if($role==0): ?>
            <li class="<?php if($current=='affiliates'){ echo 'mm-active'; } ?>">
                <a href="<?php echo site_url('admin/affiliates'); ?>" class="<?php if($current=='affiliates'){ echo 'active'; } ?>"><i class="feather-file-text"></i><span>Afiliados/as</span></a>
            </li>
            <?php endif; ?>
            <?php if($role==0): ?>
            <li class="<?php if($current=='sections'){ echo 'mm-active'; } ?>">
                <a href="<?php echo site_url('admin/sections'); ?>" class="<?php if($current=='sections'){ echo 'active'; } ?>"><i class="feather-folder"></i><span>Secciones</span></a>
            </li>
            <?php endif; ?>
            <li class="<?php if($current=='messages'){ echo 'mm-active'; } ?>">
                <a href="<?php echo site_url('admin/messages'); ?>" class="<?php if($current=='messages'){ echo 'active'; } ?>"><i class="feather-bell"></i><span>Notificaciones</span></a>
            </li>
            <?php if($role==0): ?>
            
            <li class="<?php if($current=='settings'){ echo 'mm-active'; } ?>">
                <a href="<?php echo site_url('admin/settings'); ?>" class="<?php if($current=='settings'){ echo 'active'; } ?>"><i class="feather-settings"></i><span>Configuración</span></a>
            </li>
            <li class="<?php if($current=='users'){ echo 'mm-active'; } ?>">
                <a href="<?php echo site_url('admin/users'); ?>" class="<?php if($current=='users'){ echo 'active'; } ?>"><i class="feather-users"></i><span>Usuarios/as</span></a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
    <!-- Sidebar -->
</div>
</div>
<!-- Left Sidebar End -->