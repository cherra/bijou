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
        <label class="col-sm-2" for="PERMNAME">Nombre</label>
        <div class="col-sm-6 col-md-4">
            <input type="text" id="PERMNAME" name="PERMNAME" class="form-control required" value="<?php echo (isset($datos->PERMNAME) ? $datos->PERMNAME : ''); ?>" placeholder="Nombre">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="ICON">Ícono</label>
        <div class="col-sm-6 col-md-4">
            <input type="text" id="ICON" name="ICON" class="form-control" value="<?php echo (isset($datos->ICON) ? $datos->ICON : ''); ?>" placeholder="Ícono">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="MENU">Menú</label>
        <div class="col-sm-6 col-md-4">
            <input type="checkbox" id="MENU" name="MENU" value="1" <?php 
            if(isset($datos->MENU)){
                echo $datos->MENU == 1 ? 'checked' : ''; 
            }
            ?>>
        </div>
    </div>
<?php echo form_close(); ?>

<script type="text/javascript">
    
$(function () {
   
    $('#PERMNAME').focus();
    
});

</script>