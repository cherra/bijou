<?php echo form_open($action, array('class' => 'form-horizontal', 'name' => 'form', 'id' => 'form', 'role' => 'form')) ?>
    <div class="form-group">
        <div class="col-xs-6">
            <?php echo anchor($link_back,'<span class="'.$this->config->item('icono_regresar').'"></span> Regresar'); ?>
        </div>
        <div class="col-xs-6">
            <button type="submit" id="guardar" class="btn btn-primary pull-right"><span class="<?php echo $this->config->item('icono_guardar'); ?>"></span> Guardar</button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="NAME">Nombre</label>
        <div class="col-sm-6 col-md-4">
            <input type="text" id="NAME" name="NAME" class="form-control required" value="<?php echo (isset($datos->NAME) ? $datos->NAME : ''); ?>" placeholder="Nombre" autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="APPPASSWORD">Contraseña</label>
        <div class="col-sm-6 col-md-4">
            <input type="password" id="APPPASSWORD" name="APPPASSWORD" class="form-control" placeholder="Contraseña" autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="confirmar_password">Confirmar contraseña</label>
        <div class="col-sm-6 col-md-4">
            <input type="password" id="confirmar_password" name="confirmar_password" class="form-control" placeholder="Confirmar contraseña">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="VISIBLE">Activo?</label>
        <div class="col-sm-6 col-md-4">
            <input type="checkbox" name="VISIBLE" value="1" <?php 
            if(isset($datos->VISIBLE)){
                echo ord($datos->VISIBLE) == 1 ? 'checked' : ''; 
            }
            ?>>
        </div>
    </div>
    <?php
    if(isset($roles)){
    ?>
    <div class="form-group">
        <label class="col-sm-2" for="ROLE">Rol</label>
        <div class="col-sm-6 col-md-4">
            <select name="ROLE" class="form-control">
                <option value="">Selecciona un rol...</option>
                <?php
                foreach($roles as $rol){
                ?>
                <option value="<?php echo $rol->ID; ?>" <?php if(isset($datos->ROLE) && ($datos->ROLE == $rol->ID)) echo "selected"; ?>><?php echo $rol->NAME; ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <?php
    }
    ?>
<?php echo form_close(); ?>

<script type="text/javascript">
    
$(function () {
   
    $('#NAME').focus();
    
});

</script>