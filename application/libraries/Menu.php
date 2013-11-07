<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of menu
 *
 * @author cherra
 */
class Menu {
    
    var $ci;
    var $routing;
	
    function __construct() {
        $this->ci = &get_instance();
        $this->routing =& load_class('Router');
        //$this->ci->load->model('menu');
    }
    
    function get_metodos( $folder ){
        $this->ci->db->where('FOLDER', $folder);
        $this->ci->db->where('MENU', '1');
        $this->ci->db->order_by('CLASS, METHOD');
        $query = $this->ci->db->get('PERMS');
        return  $query->result();
    }
    
    function get_clases( $folder ){
        $this->ci->db->where('LENGTH(FOLDER) > 0');
        $this->ci->db->where('MENU', '1');
        $this->ci->db->where('FOLDER', $folder);
        $this->ci->db->group_by('CLASS');
        $this->ci->db->order_by('CLASS');
        $query = $this->ci->db->get('PERMS');
        return  $query->result_array();
    }
    
    function get_folders(){
        $this->ci->db->select('FOLDER');
        $this->ci->db->where('LENGTH(FOLDER) > 0');
        $this->ci->db->where('MENU', '1');
        $this->ci->db->group_by('FOLDER');
        $this->ci->db->order_by('FOLDER');
        $query = $this->ci->db->get('PERMS');
        return  $query->result();
    }
        
    /*function menuOptions(){
        
        $folders = $this->getFolders();

        $folder = $this->ci->uri->segment(1);  // Se obtiene el directorio
        
        // Obtiene todos los métodos de los controladores que están dentro del directorio
        $submenu = $this->getMetodos($folder); 

        // Se registran los dos menús en la sesión del usuario
        // folders -> topbar
        // submenu -> leftbar
        $this->ci->session->set_userdata('folders',$folders);
        $this->ci->session->set_userdata('submenu', $submenu );
    }*/
}

?>
