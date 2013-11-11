<?php
/**
 * Description of compras
 *
 * @author cherra
 */
class Compras extends CI_Controller {
    
    private $folder = 'compras/';
    private $clase = 'compras/';
    
    function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('vacio');
    }
    
    public function compra( $offset = 0 ){
        $this->load->model('compra','c');
        $this->load->model('proveedor','p');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Compras <small>Lista</small>';
    	$data['link_add'] = $this->folder.$this->clase.'compra_agregar/'.$offset;
    	$data['action'] = $this->folder.$this->clase.'compra';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->c->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'compra');
    	$config['total_rows'] = $this->c->count_all($filtro);
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Fecha', 'Proveedor', '', '', '');
    	foreach ($datos as $d) {
            $proveedor = $this->p->get_by_id($d->SUPPLIER)->row();
            $this->table->add_row(
                    $d->DATENEW,
                    $proveedor->NAME,
                    anchor($this->folder.$this->clase.'compra_ver/' . $d->ID . '/' . $offset, '<span class="glyphicon glyphicon-edit"></span>','title="Editar"'),
                    anchor($this->folder.$this->clase.'compra_productos/' . $d->ID . '/' . $offset, '<span class="glyphicon glyphicon-th-list"></span>','title="Productos"'),
                    anchor($this->folder.$this->clase.'compra_borrar/' . $d->ID . '/' . $offset, '<span class="glyphicon glyphicon-remove"></span>','title="Borrar"')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('lista', $data);
    }
    
    public function compra_agregar( $offset = 0 ){
        $this->load->model('compra','c');
        $this->load->model('proveedor','p');
        
    	$data['titulo'] = 'Compras <small>Registro nuevo</small>';
    	$data['link_back'] = $this->folder.$this->clase.'compra/'.$offset;
    
    	$data['action'] = $this->folder.$this->clase.'compra_agregar/'.$offset;
    	if ( ($datos = $this->input->post()) ) {
            if(strlen($datos['rfc']) > 0)
                $datos['tipo_impresion'] = 'factura';
            if( ($id = $this->c->save($datos)) ){
                $this->session->set_flashdata('mensaje',$this->config->item('create_success'));
                redirect($this->folder.$this->clase.'compra_ver/'.$id.'/'.$offset);
            }else{
                $this->session->set_flashdata('mensaje',$this->config->item('error'));
                redirect($this->folder.$this->clase.'compra_agregar/'.$offset);
            }
    	}
        
        $data['proveedores'] = $this->p->get_all()->result();
        $this->load->view('compras/compras/formulario', $data);
    }
}
?>
