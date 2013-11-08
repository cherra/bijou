<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rol_model
 *
 * @author cherra
 */
class Rol extends CI_Model {
    
    private $tbl = "ROLES";
    private $tbl_permisos = "ROLEPERMS";
    
    function count_all() {
        return $this->db->count_all($this->tbl);
    }
    
    function get_all(){
        return $this->db->get($this->tbl);
    }
    
    /**
    * ***********************************************************************
    * Cantidad de registros por pagina
    * ***********************************************************************
    */
    function get_paged_list($limit = 10, $offset = 0) {
        $this->db->order_by('ID','desc');
        return $this->db->get($this->tbl, $limit, $offset);
    }
    
    /**
    * ***********************************************************************
    * Obtener rol por id
    * ***********************************************************************
    */
    function get_by_id($id) {
        $this->db->where('ID', $id);
        return $this->db->get($this->tbl);
    }
    
    /**
    * ***********************************************************************
    * Alta de rol
    * ***********************************************************************
    */
    function save($rol) {
        $this->db->insert($this->tbl, $rol);
        return $this->db->insert_id();
    }

    /**
    * ***********************************************************************
    * Actualizar rol por id
    * ***********************************************************************
    */
    function update($id, $rol) {
        $this->db->where('ID', $id);
        $this->db->update($this->tbl, $rol);
    }

    /**
    * ***********************************************************************
    * Eliminar rol por id
    * ***********************************************************************
    */
    function delete($id) {
        $this->db->where('ID', $id);
        $this->db->delete($this->tbl);
        return $this->db->affected_rows();
    }
    
    /**
    * ***********************************************************************
    * Obtener permisos del rol por id
    * ***********************************************************************
    */
    function get_permiso_by_id($id_permiso, $id_rol) {
        $this->db->where('ROLEID', $id_rol);
        $this->db->where('PERMID', $id_permiso);
        return $this->db->get($this->tbl_permisos);
    }
    
    function update_permisos( $id, $permisos ){
        $this->db->delete($this->tbl_permisos, array('ROLEID' => $id));
        if(!empty($permisos)){
            $this->db->insert_batch($this->tbl_permisos, $permisos);
        }
        return $this->db->affected_rows();
    }
    
}

?>
