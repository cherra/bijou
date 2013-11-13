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
    
    public function entradas( $offset = 0 ){
        $this->load->model('stock','s');
        $this->load->model('producto','p');
        $this->load->model('sucursal','su');
        $this->load->model('usuario','u');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Entradas <small>Lista</small>';
    	$data['link_add'] = $this->folder.$this->clase.'entradas_agregar/'.$offset;
    	$data['action'] = $this->folder.$this->clase.'entradas';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->s->get_paged_list_entradas($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'entradas');
    	$config['total_rows'] = $this->s->count_all_entradas($filtro);
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Fecha', 'Producto', 'C', array('data'=> 'Sucursal', 'class' => 'hidden-xs'), '', '');
    	foreach ($datos as $d) {
            $producto = $this->p->get_by_id($d->PRODUCT)->row();
            $sucursal = $this->su->get_by_id($d->LOCATION)->row();
            //$usuario = $this->su->get_by_id($d->PERSON)->row();
            $this->table->add_row(
                    $d->DATENEW,
                    $producto->NAME,
                    $d->UNITS,
                    array('data'=> $sucursal->NAME, 'class' => 'hidden-xs'),
                    //array('data'=> (!empty($usuario->NAME) ? $usuario->NAME : ''), 'class' => 'hidden-xs'),
                    anchor($this->folder.$this->clase.'entradas_ver/' . $d->ID . '/' . $offset, '<span class="glyphicon glyphicon-edit"></span>','title="Editar"'),
                    anchor($this->folder.$this->clase.'entradas_borrar/' . $d->ID . '/' . $offset, '<span class="glyphicon glyphicon-remove"></span>','title="Borrar"')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('lista', $data);
    }
    
    /*
     * Agregar una entrada
     */
    
    public function entradas_agregar( $offset = 0 ){
        $this->load->model('stock','s');
        $this->load->model('sucursal','su');
        $this->load->model('proveedor','p');
        $this->load->model('producto','pr');
        $this->load->model('categoria','c');
        
        if( $this->input->is_ajax_request() ){
            if ( ($datos = $this->input->post()) ) {
                $this->load->library('uuid');
                $this->load->library('etiqueta');  // Para generar las etiquetas
                $this->load->helper('file');

                $data['proveedor'] = $this->p->get_by_id($datos['SUPPLIER'])->row();
                $data['sucursal'] = $this->su->get_by_id($datos['LOCATION'])->row();
                $data['categoria'] = $this->c->get_by_id($datos['CATEGORY'])->row();

                /*
                 * Se genera el nuevo producto
                 */
                $this->db->trans_start(); // Inicio de transacción en la base de datos
                $id_producto = $this->uuid->v4();
                $codigo = $this->pr->genera_codigo($datos['SUPPLIER'], $datos['CATEGORY']);
                $producto = array(
                    'ID' => $id_producto,
                    'CODE' => $codigo,
                    'NAME' => $datos['NAME'],
                    'PRICESELL' => $datos['PRICE'],
                    'CATEGORY' => $datos['CATEGORY'],
                    'SUPPLIER' => $datos['SUPPLIER'],
                    'TAXCAT' => '000'
                );
                if( $this->pr->save($producto) > 0 ){
                    $id_entrada = $this->uuid->v4();
                    $entrada = array(
                        'ID' => $id_entrada,
                        'DATENEW' => date('Y-m-d H:i:s'),
                        'REASON' => '1',
                        'LOCATION' => $datos['LOCATION'],
                        'PRODUCT' => $id_producto,
                        'UNITS' => $datos['UNITS'],
                        'PRICE' => $datos['PRICE']
                    );

                    if( $this->s->save($entrada) > 0 ){
                        $etiqueta = $this->etiqueta->genera($this->config->item('label_format'), $datos['NAME'], $datos['PRICE'], $codigo, '', $datos['UNITS'], $datos['REFERENCE']);
                        if($etiqueta){
                            if(write_file($this->config->item('asset_path').$this->config->item('label_file'), $etiqueta)){
                                echo "OK";
                            }else
                                echo "Error: No se pudo escribir en disco el archivo de la etiqueta.";
                            //exec('lp -d '.$this->config->item('label_printer').' -h '.$this->config->item('label_host').' -o raw labels/'.$this->config->item('label_file'));
                        }else{
                            echo "Error al generar la etiqueta: ".$this->config->item('asset_path').$this->config->item('label_format');
                        }
                    }else{
                        echo "Error al guardar la etiqueta en disco";
                    }
                }
                $this->db->trans_complete();
            }
        }else{
            $data['titulo'] = 'Entradas <small>Registro nuevo</small>';
            $data['link_back'] = $this->folder.$this->clase.'entradas/'.$offset;

            $data['action'] = $this->folder.$this->clase.'entradas_agregar/'.$offset;

            $data['categorias'] = $this->c->get_all()->result();
            $data['proveedores'] = $this->p->get_all()->result();
            $data['sucursales'] = $this->su->get_all()->result();
            $this->load->view('inventario/entradas/formulario', $data);
        }
    }
}
?>
