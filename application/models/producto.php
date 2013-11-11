<?php
/**
 * Description of producto
 *
 * @author cherra
 */
class Producto extends CI_Model {
    
    private $tbl = 'PRODUCTS';
    
    /*
     * Cuenta todos los registros utilizando un filtro de busqueda
     */
    function count_all( $filtro = NULL ) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('NAME',$f);
                $this->db->or_like('CODE',$f);
            }
        }
        $query = $this->db->get($this->tbl);
        return $query->num_rows();
    }
    
    /**
     *  Obtiene todos los registros de la tabla
     */
    function get_all() {
        $this->db->order_by('ID','asc');
        return $this->db->get($this->tbl);
    }
    
    /**
    * Cantidad de registros por pagina
    */
    function get_paged_list($limit = NULL, $offset = 0, $filtro = NULL) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('NAME',$f);
                $this->db->or_like('CODE',$f);
            }
        }
        $this->db->order_by('ID','asc');
        return $this->db->get($this->tbl, $limit, $offset);
    }
    
    /**
    * Obtener por id
    */
    function get_by_id($id) {
        $this->db->where('ID', $id);
        return $this->db->get($this->tbl);
    }
    
    /**
    * Alta
    */
    function save( $datos ) {
        $this->db->insert($this->tbl, $datos);
        return $this->db->affected_rows();
    }

    /**
    * Actualizar por id
    */
    function update($id, $datos) {
        $this->db->where('ID', $id);
        $this->db->update($this->tbl, $datos);
    }

    /**
    * Eliminar por id
    */
    function delete($id) {
        $this->db->where('ID', $id);
        $this->db->delete($this->tbl);
    } 
    
    
    /*
     * Generar un código
     */
    
    public function genera_codigo($proveedor, $categoria){
        $this->db->where('ID', $proveedor);
        $query = $this->db->get('SUPPLIERS');
        if($query->num_rows() > 0){
            $row = $query->row();
            $barcode = $row->PREFIX;
        }else{
            return false;
        }
        
        $this->db->select('UPPER(LEFT(NAME,2)) AS CAT_PREFIX',false);
        $this->db->where('ID', $categoria);
        $query = $this->db->get('CATEGORIES');
        if($query->num_rows() > 0){
            $row = $query->row();
            $barcode .= $row->CAT_PREFIX;
        }else{
            return false;
        }
        
        $this->db->select('MAX(RIGHT(CODE,4)) AS CODIGO',false);
        $this->db->where('LEFT(CODE,4)',$barcode);
        $query = $this->db->get('PRODUCTS');
        if($query->num_rows() > 0){
            $row = $query->row();
            $barcode .= str_pad($row->CODIGO+1, 4, "0", STR_PAD_LEFT);
        }else{
            $barcode .= "0001";
        }
        
        return $barcode;
    }
}
?>
