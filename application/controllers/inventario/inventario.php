<?php
/**
 * Description of inventario
 *
 * @author cherra
 */
class Inventario extends CI_Controller {
    
    private $folder = 'inventario/';
    private $clase = 'inventario/';
    
    function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('vacio');
    }
}
?>
