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
        <label class="col-sm-2" for="SUPPLIER">Proveedor</label>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <select name="SUPPLIER" class="form-control required">
                <option value="">Selecciona un proveedor...</option>
                <?php
                foreach($proveedores AS $p){
                ?>
                    <option value="<?php echo $p->ID; ?>" <?php if(isset($datos->SUPPLIER) && ($datos->SUPPLIER == $p->ID)) echo "selected"; ?>><?php echo $p->NAME; ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="NUMBER">Número</label>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <input type="text" name="NUMBER" class="form-control" value="<?php echo (isset($datos->NUMBER) ? $datos->NUMBER : ''); ?>" placeholder="Número">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="DATENEW">Fecha</label>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <input type="text" name="DATENEW" class="form-control required" value="<?php echo (isset($datos->DATENEW) ? $datos->DATENEW : ''); ?>" placeholder="Fecha">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="TOTAL">Importe total</label>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <input type="text" name="TOTAL" class="form-control required" value="<?php echo (isset($datos->TOTAL) ? $datos->TOTAL : ''); ?>" placeholder="Total">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="NOTES">Observaciones</label>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <textarea name="NOTES" class="form-control" placeholder="Observaciones"><?php echo (isset($datos->NOTES) ? $datos->NOTES : ''); ?></textarea>
        </div>
    </div>
<?php echo form_close(); ?>

<script type="text/javascript">
    
$(function () {
   
    $('#nombre').focus();
    
});

</script>