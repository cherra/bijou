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
            <p><?php echo (isset($datos->NAME) ? $datos->NAME : ''); ?></p>
            <input type="hidden" name="nada" /> <!-- Solo para que valide TRUE la petición POST en caso de que no se seleccione ningún permiso -->
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="menu">Marcar todos</label>
        <div class="col-sm-6 col-md-4">
            <input type="checkbox" id="marcar_todos">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
        <?php
        echo $table;
        ?>
        </div>
    </div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function(){
        $('#marcar_todos').change(function(){
            if($(this).is(':checked')){
                $('table input[type="checkbox"]').attr('checked','checked');
            }
        });
    });
</script>