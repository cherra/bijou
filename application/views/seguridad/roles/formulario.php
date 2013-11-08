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
            <input type="text" id="NAME" name="NAME" class="form-control required" value="<?php echo (isset($datos->NAME) ? $datos->NAME : ''); ?>" placeholder="Nombre">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="DESCRIPTION">Descripción</label>
        <div class="col-sm-6 col-md-4">
            <input type="text" id="DESCRIPTION" name="DESCRIPTION" class="form-control" value="<?php echo (isset($datos->DESCRIPTION) ? $datos->DESCRIPTION : ''); ?>" placeholder="Descripción">
        </div>
    </div>
<?php echo form_close(); ?>

<script type="text/javascript">
    
$(function () {
   
    $('#NAME').focus();
    
});

</script>