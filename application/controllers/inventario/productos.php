<?php

/**
 * Description of productos
 *
 * @author cherra
 */
class Productos extends CI_Controller {
    
    private $folder = 'inventario/';
    private $clase = 'productos/';
    
    function __construct() {
        parent::__construct();
    }
    
    public function articulos( $offset = 0 ){
        $this->load->model('producto','p');
        $this->load->model('categoria','c');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Artículos <small>Lista</small>';
    	//$data['link_add'] = $this->folder.$this->clase.'articulos_agregar/'. $offset;
    	$data['action'] = $this->folder.$this->clase.'articulos';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->p->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'articulos');
    	$config['total_rows'] = $this->p->count_all($filtro);
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Nombre', 'Código', 'Categoría', 'Precio', '');
    	foreach ($datos as $d) {
            $categoria = $this->c->get_by_id($d->CATEGORY)->row();
            $this->table->add_row(
                    $d->NAME,
                    $d->CODE,
                    $categoria->NAME,
                    array('data' => number_format($d->PRICESELL,2), 'class' => 'text-right'),
                    //anchor($this->folder.$this->clase.'articulos_ver/' . $d->ID . '/' . $offset, '<span class="'.$this->config->item('icono_editar').'"></span>'),
                    array('data' => anchor('#', '<span class="glyphicon glyphicon-print"></span>',array('title' => "Imprimir etiqueta", 'id' => $d->ID, 'class' => 'imprimir')), 'class' => 'visible-lg')
            );
    	}
        $data['action_imprimir'] = $this->folder.$this->clase.'imprimir_etiqueta';
    	$data['table'] = $this->table->generate();
    
    	$this->load->view('inventario/productos/lista', $data);
    }
    
    public function imprimir_etiqueta( ){
        if($this->input->is_ajax_request()){
            if( ($datos = $this->input->post()) ){
                $this->load->model('producto','p');

                $this->load->library('etiqueta');  // Para generar las etiquetas
                $this->load->helper('file');

                $producto = $this->p->get_by_id($datos['ID'])->row();
//                echo "ID: ".$datos['ID'];
//                echo var_dump($producto);

                $etiqueta = $this->etiqueta->genera($this->config->item('label_format'), $producto->NAME, $producto->PRICESELL, $producto->CODE, '', '1', $producto->REFERENCE);
                if($etiqueta){
                    if(write_file($this->config->item('asset_path').$this->config->item('label_file'), $etiqueta)){
                        echo "OK";
                    }else{
                        echo "Error al generar la etiqueta";
                    }
                }else{
                    echo "Error al generar la etiqueta: ".$this->config->item('asset_path').$this->config->item('label_format');
                }
            }
        }
    }
}
?>
