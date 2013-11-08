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
class Usuario extends CI_Model {
    
    private $tbl = "PEOPLE";
    private $tbl_permisos = "PEOPLEPERMS";
    private $tbl_roles = "PEOPLEROLES";
    
    function count_all( $filtro = NULL ) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('NAME', $f);
            }
        }
        $query = $this->db->get($this->tbl);
        return $query->num_rows;
    }
    
    /**
    * ***********************************************************************
    * Cantidad de registros por pagina
    * ***********************************************************************
    */
    function get_paged_list($limit = null, $offset = 0, $filtro = NULL) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('NAME', $f);
            }
        }
        $this->db->order_by('NAME','asc');
        return $this->db->get($this->tbl, $limit, $offset);
    }
    
    /**
    * ***********************************************************************
    * Obtener usuario por id
    * ***********************************************************************
    */
    function get_by_id($id) {
        $this->db->where('ID', $id);
        return $this->db->get($this->tbl);
    }
    
    /**
    * ***********************************************************************
    * Alta de usuario
    * ***********************************************************************
    */
    function save($usuario) {
        $this->db->insert($this->tbl, $usuario);
        return $this->db->insert_id();
    }

    /**
    * ***********************************************************************
    * Actualizar usuario por id
    * ***********************************************************************
    */
    function update($id, $usuario) {
        $this->db->where('ID', $id);
        $this->db->update($this->tbl, $usuario);
    }

    /**
    * ***********************************************************************
    * Eliminar usuario por id
    * ***********************************************************************
    */
    function delete($id) {
        $this->db->where('ID', $id);
        $this->db->delete($this->tbl);
        return $this->db->affected_rows();
    }
    
    /**
    * ***********************************************************************
    * Obtener permisos del usuario por id
    * ***********************************************************************
    */
    function get_permiso_by_id($id_permiso, $id_usuario) {
        $this->db->where('USERID', $id_usuario);
        $this->db->where('PERMID', $id_permiso);
        return $this->db->get($this->tbl_permisos);
    }
    
    function update_permisos( $id, $permisos ){
        $this->db->delete($this->tbl_permisos, array('USERID' => $id));
        if(!empty($permisos)){
            $this->db->insert_batch($this->tbl_permisos, $permisos);
        }
        return $this->db->affected_rows();
    }
    
    /**
    * ***********************************************************************
    * Obtener roles del usuario por id
    * ***********************************************************************
    */
    function get_rol_by_id($id_rol, $id_usuario) {
        $this->db->where('USERID', $id_usuario);
        $this->db->where('ROLEID', $id_rol);
        return $this->db->get($this->tbl_roles);
    }
    
    function update_roles( $id, $roles ){
        $this->db->delete($this->tbl_roles, array('USERID' => $id));
        if(!empty($roles)){
            $this->db->insert_batch($this->tbl_roles, $roles);
        }
        return $this->db->affected_rows();
    }

}

?>
