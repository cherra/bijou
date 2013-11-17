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
		$this->db->where('(p.NAME LIKE "%'.$f.'%"'.
                        ' OR p.CODE = "'.$f.'"'.
                        ' OR p.REFERENCE = "'.$f.'"'.
                        ' OR c.NAME LIKE "%'.$f.'%")');
                /*$this->db->or_like('p.NAME',$f);
                $this->db->or_like('p.CODE',$f);
                $this->db->or_like('p.REFERENCE',$f);
                $this->db->or_like('c.NAME',$f);*/
            }
        }
        $this->db->join('CATEGORIES c','p.CATEGORY = c.ID');
        $query = $this->db->get($this->tbl.' p');
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
        $this->db->select('p.*');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
		$this->db->where('(p.NAME LIKE "%'.$f.'%"'.
			' OR p.CODE = "'.$f.'"'.
			' OR p.REFERENCE = "'.$f.'"'.
			' OR c.NAME LIKE "'.$f.'")');
                /*$this->db->or_like('p.NAME',$f);
                $this->db->or_like('p.CODE',$f);
                $this->db->or_like('p.REFERENCE',$f);
                $this->db->or_like('c.NAME',$f);*/
            }
        }
        $this->db->join('CATEGORIES c','p.CATEGORY = c.ID');
        $this->db->order_by('p.NAME','asc');
        return $this->db->get($this->tbl.' p', $limit, $offset);
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
