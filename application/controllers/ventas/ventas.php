<?php
/**
 * Description of ventas
 *
 * @author cherra
 */
class Ventas extends CI_Controller {
    
    private $folder = 'ventas/';
    private $clase = 'ventas/';
    
    function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('vacio');
    }
}
?>
