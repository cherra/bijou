<?php echo form_open($action, array('class' => 'form-horizontal', 'name' => 'form', 'id' => 'form', 'role' => 'form')) ?>
    <div class="form-group">
        <div class="col-xs-6">
            <?php echo anchor($link_back,'<span class="glyphicon glyphicon-home"></span> Inicio',array('class'=>'')); ?>
        </div>
        <div class="col-xs-6">
            <button type="submit" id="guardar" class="btn btn-primary pull-right"><span class="<?php echo $this->config->item('icono_guardar'); ?>"></span> Guardar</button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="nombre">Nombre</label>
        <div class="col-sm-6 col-md-4">
            <p><?php echo (isset($datos->nombre) ? $datos->nombre : ''); ?></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="apellido">Apellidos</label>
        <div class="col-sm-6 col-md-4">
            <p><?php echo (isset($datos->apellido) ? $datos->apellido : ''); ?></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="username">Nombre de usuario</label>
        <div class="col-sm-6 col-md-4">
            <p><?php echo (isset($datos->username) ? $datos->username : ''); ?></p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="password">Contraseña</label>
        <div class="col-sm-6 col-md-4">
            <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="confirmar_password">Confirmar contraseña</label>
        <div class="col-sm-6 col-md-4">
            <input type="password" id="confirmar_password" name="confirmar_password" class="form-control" placeholder="Confirmar contraseña">
        </div>
    </div>
<?php echo form_close(); ?>

<script type="text/javascript">
    
$(function () {
   
    $('#password').focus();
    
});

</script>