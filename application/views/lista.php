<?php
    if(isset($link_back)){
    ?>
    <div class="row">
        <div class="col-xs-6">
            <p><?php echo anchor($link_back,'<span class="'.$this->config->item('icono_regresar').'"></span> Regresar'); ?></p>
        </div>
    </div>
    <?php
    }
    ?>
<?php echo form_open($action, array('class' => 'form-inline', 'name' => 'form', 'id' => 'form', 'role' => 'form')) ?>
    <div class="form-group">
        <label class="sr-only" for="filtro">Filtros</label>
        <input type="text" class="form-control" name="filtro" id="filtro" placeholder="Filtros de busqueda" value="<?php if(isset($filtro)) echo $filtro; ?>" >
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary"><span class="<?php echo $this->config->item('icono_buscar'); ?>"></span></button>
    </div>
<?php echo form_close(); ?>
<div class="row">
    <div class="col-xs-12 col-sm-9 col-md-10">
        <?php echo $pagination; ?>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-2">
        <?php if(isset($link_add)){ ?>
        <p class="text-right"><?php echo anchor($link_add,'<span class="'.$this->config->item('icono_nuevo').'"></span> Nuevo', array('class' => 'btn btn-default btn-block')); ?></p>
        <?php } ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php echo $table; ?>
    </div>
</div>
<script>
$(document).ready(function(){
    $('#filtro').focus();
});
</script>