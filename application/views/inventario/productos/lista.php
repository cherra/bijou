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
<applet id="qz" archive="<?php echo asset_url().'qz-print/qz-print.jar'; ?>" name="QZ Print Plugin" code="qz.PrintApplet.class" width="1" height="1">
<!--    <param name="jnlp_href" value="<?php echo asset_url().'qz-print/qz-print_jnlp.jnlp'; ?>">-->
<!--    <param name="cache_option" value="plugin">
    <param name="disable_logging" value="false">-->
</applet>

<script>

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


$(document).ready(function(){
    $('#filtro').focus();
    
    $('.imprimir').click(function(event){
        event.preventDefault();
    
        var id = $(this).attr('id');
        $.ajax({
            url: "<?php echo site_url($action_imprimir); ?>",
            type: 'post',
            data: {ID: id},
            dataType: 'text'
        }).done(function(respuesta){
            console.log(respuesta);
            if(respuesta == 'OK'){
                printFile("<?php echo asset_url().$this->config->item('label_file'); ?>");
            }else{
                console.log("No se pudo imprimir");
            }
        }).fail(function(){
            console.log("Error al intentar guardar la compra");
        });
    });
});
</script>