<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?php echo $this->config->item('nombre_proyecto'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <!-- css -------------------------------------------------------------------- -->
    <link href="<?php echo asset_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="<?php echo asset_url(); ?>bootstrap/css/bootstrap-theme.min.css" rel="stylesheet"> -->
   
  <!-- js ---------------------------------------------------------------------- -->
    <script src="<?php echo asset_url(); ?>js/jquery.min.js"></script>
    <script src="<?php echo asset_url(); ?>bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo asset_url(); ?>js/jquery.validate.js"></script>
    <script src="<?php echo asset_url(); ?>js/messages_es.js"></script>
    <style>
        body { 
            padding-top: 55px; 
/*            padding-bottom: 60px;*/
        }
        
        .sidebar-nav{
            margin-top: 10px;
            padding: 9px 0px;
        }
        
        .sidebar-nav .nav li{
            margin-left: 10px;
        }
        
        .sidebar-nav .nav li.nav-header{
            margin-top: 10px;
        }
        
        .sidebar-nav .nav li a{
            padding: 5px 10px;
        }
        
        .page-header{
            margin: 0px 0 15px;
            padding-bottom: 5px;
        }
        
        .navbar-fixed-top{
            margin-bottom: 0px;
        }
        
        .pagination{
            margin: 10px 0;
        }
    </style>
</head>
<body>

<!-- menu-top ---------------------------------------------------------------- -->
<nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <?php echo anchor(site_url(), $this->config->item('nombre_proyecto'), 'class="navbar-brand"'); ?>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
            <?php
                // Se obtienen los folders de los métodos para mostrarlos en la barra superior.
                $folders = $this->menu->get_folders();
                $folder_activo = false;
                foreach($folders as $folder){ ?>
                <li <?php 
                // Si el primer segmento del URI es igual al folder quiere decir que es la opción seleccionada
                // y se marca como activa para resaltarla
                if( $this->uri->segment(1) == $folder->FOLDER){
                    echo 'class="active"';
                    $folder_activo = $folder->FOLDER;
                }
                ?>><?php 
                echo anchor($folder->FOLDER.'/'.$folder->FOLDER, ucfirst(strtolower($folder->FOLDER)), 'class="navbar-link"'); ?></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown pull-right">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> Mi cuenta<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><?php echo anchor('seguridad/usuarios_password','Cambiar contraseña'); ?></li>
                        <li><?php echo anchor('login/do_logout','Salir'); ?></li>
                    </ul>
                </li>
                <li class="dropdown pull-right">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Configuración<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><?php echo anchor('seguridad/permisos_lista', 'Permisos'); ?></li>
                        <li><?php echo anchor('seguridad/roles_lista', 'Roles'); ?></li>
                        <li><?php echo anchor('seguridad/usuarios_lista', 'Usuarios'); ?></li>
                        <li class="divider"></li>
                        <li><a href="#">Parámetros</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Plantillas</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!--<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
  <button type="button" class="btn btn-default navbar-btn">Sign in</button>

</nav>-->

<div class="container">
    <div class="row">
        <?php 
        // Si hay submenú :
        if($folder_activo){ ?>
        
            <div class="col-sm-3 col-lg-2">
                <div class="sidebar-nav well hidden-print">
                    <ul class="nav nav-list">
                        <?php
                        $clase = '';
                        $metodos = $this->menu->get_metodos($folder_activo);
                        foreach ( $metodos as $metodo ){
                            if($clase != $metodo->CLASS){
                                $clase = $metodo->CLASS;
                        ?>
                        <li class="nav-header text-muted"><?php echo strtoupper(str_replace('_',' ',$metodo->CLASS)); ?></li>
                        <?php
                            }
                            // Link para el menú
                            $link = $metodo->FOLDER.'/'.$metodo->CLASS.'/'.$metodo->METHOD;
                        ?>
                                <li <?php 
                                // Si el link es igual al URI quiere decir que es la opción seleccionada
                                // y se marca como activa para resaltarla
                                if( strpos(current_url(), $metodo->FOLDER.'/'.$metodo->CLASS.'/'.$metodo->METHOD) ) 
                                    echo 'class="active"'; 
                                ?>><?php echo anchor($link, '<span class="glyphicon glyphicon-'.$metodo->ICON.'"></span> '.$metodo->PERMNAME); ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="col-sm-9 col-lg-10">
        <?php 
        }else{ 
        ?>
            <div class="col-sm-12">
        <?php
        } 
            if(isset($titulo)){
        ?>
                <!-- Encabezado de la página -->
                <div class="page-header">
                <h2><?php echo $titulo; ?></h2>
                </div>
        <?php
            }
        ?>
                
                <!-- contenido --------------------------------------------------------------- -->
                {contenido_vista}
                
                
            </div>
            <div class="col-sm-offset-3 col-lg-offset-2 col-sm-4 col-lg-5">
                    <p><?php if($this->session->flashdata('mensaje')) echo $this->session->flashdata('mensaje') ?></p>
            </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('form').validate({
            errorClass: "has-error",
            validClass: "has-success",
            rules: {
                confirmar_password: {
                    equalTo: "#APPPASSWORD"
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).parent().parent().addClass(errorClass).fadeOut(function() {
                  $(element).parent().parent().fadeIn();
                });
            },
            unhighlight: function(element, errorClass, validClass){
                $(element).parent().parent().removeClass(errorClass);
            }
        });
    });
</script>
</body>
</html>
