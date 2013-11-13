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
        <label class="col-sm-2" for="LOCATION">Sucursal</label>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <select name="LOCATION" class="form-control required">
                <option value="">Selecciona una sucursal...</option>
                <?php
                foreach($sucursales AS $s){
                ?>
                    <option value="<?php echo $s->ID; ?>" <?php if(isset($sucursal->ID) && ($sucursal->ID == $s->ID)) echo "selected"; ?>><?php echo $s->NAME; ?></option>
                <?php
                }
                ?>
            </select>
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
                    <option value="<?php echo $p->ID; ?>" <?php if(isset($proveedor->ID) && ($proveedor->ID == $p->ID)) echo "selected"; ?>><?php echo $p->PREFIX; ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="CATEGORY">Categoría</label>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <select name="CATEGORY" class="form-control required">
                <option value="">Selecciona una categoría...</option>
                <?php
                foreach($categorias AS $c){
                ?>
                    <option value="<?php echo $c->ID; ?>" <?php if(isset($categoria->ID) && ($categoria->ID == $c->ID)) echo "selected"; ?>><?php echo $c->NAME; ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="NAME">Nombre artículo</label>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <input type="text" name="NAME" id="NAME" class="form-control required" value="<?php echo (isset($producto->NAME) ? $producto->NAME : ''); ?>" placeholder="Artículo">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="REFERENCE">Código proveedor</label>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <input type="text" name="REFERENCE" id="REFERENCE" class="form-control required" value="<?php echo (isset($producto->REFERENCE) ? $producto->REFERENCE : ''); ?>" placeholder="Código proveedor">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="UNITS">Cantidad</label>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <input type="text" name="UNITS" id="UNITS" class="form-control required" value="<?php echo (isset($datos->UNITS) ? $datos->UNITS : ''); ?>" placeholder="Cantidad">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="PRICE">Precio</label>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <input type="text" name="PRICE" id="PRICE" class="form-control required" value="<?php echo (isset($datos->PRICE) ? $datos->PRICE : ''); ?>" placeholder="Precio">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2" for="imprimir">Imprimir etiqueta</label>
        <div class="col-sm-8 col-md-6 col-lg-4">
            <input type="checkbox" name="imprimir" id="imprimir" value="1" checked />
        </div>
    </div>
<?php echo form_close(); ?>
<div class="alert alert-success alert-dismissable" style="display: none;" id="alerta">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><span id="mensaje"></span>
</div>
<applet id="qz" archive="<?php echo asset_url().'qz-print/qz-print.jar'; ?>" name="QZ Print Plugin" code="qz.PrintApplet.class" width="1" height="1">
<!--    <param name="jnlp_href" value="<?php echo asset_url().'qz-print/qz-print_jnlp.jnlp'; ?>">-->
<!--    <param name="cache_option" value="plugin">
    <param name="disable_logging" value="false">-->
</applet>

<script type="text/javascript">
//
//function getPath() {
//    var path = window.location.href;
//    return path.substring(0, path.lastIndexOf("/")) + "/";
//}

var qz;
//var imprimir = 0;
    
function monitorFinding() {

    if (qz) {
       if (!qz.isDoneFinding()) {
          window.setTimeout('monitorFinding()', 100);
       } else {
          var printer = qz.getPrinter();
          console.log(printer == null ? "Printer not found" : "Printer \"" + printer + "\" found and selected.");
       }
    } else {
        alert("Error al cargar el applet para impresión.\nNo se pueden imprimir etiquetas.");
    }
}

function findPrinter() {
    if (qz) {
       // Searches for locally installed printer wiht specified name
       qz.findPrinter("<?php echo $this->config->item('label_printer'); ?>");
    }

    // *Note:  monitorFinding() still works but is too complicated and
    // outdated.  Instead create a JavaScript  function called 
    // "qzDoneFinding()" and handle your next steps there.
    monitorFinding();
 }

function monitorPrinting() {

    if (qz) {
        if (!qz.isDonePrinting()) {
           window.setTimeout('monitorPrinting()', 100);
        } else {
           var e = qz.getException();
           console.log(e ? "Exception occured: " + e.getLocalizedMessage() : "Impresión correcta!");
           qz.clearException();
        }
    } else {
        alert("Error al cargar el applet para impresión.\nNo se pueden imprimir etiquetas.");
    }
}

function printFile(file) {

    if (qz) {
       // Using qz-print's "appendFile()" function, a file containg your raw EPL/ZPL
       // can be sent directly to the printer
       // Example: 
       //     qz.appendFile("http://yoursite/zpllabel.txt"); // ...etc
       qz.appendFile(file);
       qz.print();
    }else{
        alert("Error al cargar el applet para impresión de etiquetas");
    }

    // *Note:  monitorPrinting() still works but is too complicated and
    // outdated.  Instead create a JavaScript  function called 
    // "qzDonePrinting()" and handle your next steps there.
    monitorPrinting();
}

qz = document.getElementById('qz');
if(qz){
    findPrinter();
}

var validator = $('#form').validate({
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
    },
    submitHandler: function(){
        guardar_producto();
    }
});

function guardar_producto(){
    console.log($('#form').serialize());

    $.ajax({
        url: "<?php echo site_url($action); ?>",
        type: 'post',
        data: $('#form').serialize(),
        dataType: 'text'
    }).done(function(respuesta){
        console.log(respuesta);
        if(respuesta == 'OK'){
            $('#mensaje').text('Registro creado con éxito');
            if($('#imprimir').is(':checked')){
                printFile("<?php echo asset_url().$this->config->item('label_file'); ?>");
                $('#mensaje').append('<br>Impresión enviada');
            }
            $('#NAME').val('');
            $('#REFERENCE').val('');
            $('#UNITS').val('');
            $('#PRICE').val('');
            if($('#alerta').hasClass('alert-danger'))
                $('#alerta').removeClass('alert-danger').addClass('alert-success');
        }else{
            if($('#alerta').hasClass('alert-success'))
                $('#alerta').removeClass('alert-success').addClass('alert-danger');
            $('#mensaje').text('Error al registrar el artículo!');
        }
        $('#NAME').focus();
        $('.alert').css('display','block');
    }).fail(function(){
        console.log("Error al intentar guardar la compra");
    });

    validator.resetForm();
}

$('#NAME').focus();

</script>