<?php

class Seguridad extends CI_Controller{
    
    private $folder = '';
    private $clase = 'seguridad/';
    /**
    * *****************************************************************
    * titulo para el CRUD
    * *****************************************************************
    */
    private $titulo = 'Seguridad';
    
    function __construct() {
        parent::__construct();
    }
    
    public function permisos_lista( $offset = '0' ){
        
        $this->load->model('permiso','p');
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $this->config->load("pagination");
        $page_limit = $this->config->item("per_page");
        $permisos = $this->p->get_paged_list($page_limit, $offset)->result();
        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url('seguridad/permisos_lista/');
        $config['total_rows'] = $this->p->count_all();
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array ( 'table_open'  => '<table class="' . $this->config->item('tabla_css') . '">' );
        $this->table->set_template($tmpl);
        $this->table->set_heading('Nombre', 'Ruta', 'Menú', 'Icono', '', '');
        foreach ($permisos as $permiso) {
                $this->table->add_row(
                        $permiso->PERMNAME, 
                        $permiso->FOLDER.'/'.$permiso->CLASS.'/'.$permiso->METHOD,
                        ($permiso->MENU == 1 ? 'Si' : '-'),
                        '<span class="glyphicon glyphicon-'.$permiso->ICON.'"></span>',
                        anchor('seguridad/permisos_update/' . $permiso->ID . '/' . $offset, '<span class="glyphicon glyphicon-edit"></span>'),
                        anchor('seguridad/permisos_delete/' . $permiso->ID . '/' . $offset, '<span class="glyphicon glyphicon-remove"></span>')
                );
        }
        $data['table'] = $this->table->generate();
        $data['titulo'] = 'Permisos <small>Lista</small>';
        $data['action'] = 'seguridad/permisos_lista';

        $this->load->view('lista', $data);
    }
    
    /**
	* *****************************************************************
	* Muestra en pantalla el formulario para editar un permiso
	* *****************************************************************
	*/
    public function permisos_update( $id = NULL, $offset = 0 ) {

        if (empty($id)) {
                redirect('seguridad/permisos_lista');
        }
        
        $this->load->model('permiso','p');

        $data['titulo'] = 'Permisos <small>Modificar</small>';
        $data['atributos_form'] = array('id' => 'form', 'class' => 'form-horizontal');
        $data['link_back'] = 'seguridad/permisos_lista/'.$offset;

        $data['mensaje'] = '';
        $data['action'] = 'seguridad/permisos_update/' . $id . '/' . $offset;

        if ( ($permiso = $this->input->post()) ){
            $this->p->update($id, $permiso);
            $this->session->set_flashdata('mensaje',$this->config->item('update_success'));
            redirect($this->folder.$this->clase.'permisos_update/'.$id . '/' . $offset);
        }
        $data['datos'] = $this->p->get_by_id($id)->row();
        $this->load->view('seguridad/permisos/formulario', $data);
    }
    
    public function permisos_delete( $id, $offset = 0 ){
        if (!empty($id)) {
            $this->load->model('permiso', 'p');
            $resultado = $this->p->delete($id);
            if($resultado > 0)
                $this->session->set_flashdata('mensaje',$this->config->item('update_success'));
            else
                $this->session->set_flashdata('mensaje',$this->config->item('error'));
        }
        redirect('seguridad/permisos_lista/'.$offset);
    }
    
    public function roles_lista( $offset = 0 ){
        
        $this->load->model('rol','c');
        $this->titulo = "Roles";
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        // obtener datos
        $this->config->load("pagination");
        $page_limit = $this->config->item("per_page");
        $roles = $this->c->get_paged_list($page_limit, $offset)->result();

        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url('preferencias/seguridad/roles_lista/');
        $config['total_rows'] = $this->c->count_all();
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array ( 'table_open'  => '<table class="' . $this->config->item('tabla_css') . '">' );
        $this->table->set_template($tmpl);
        $this->table->set_heading('Nombre', 'Descripción', '', '', '');
        foreach ($roles as $rol) {
                $this->table->add_row(
                        $rol->NAME,
                        $rol->DESCRIPTION,
                        anchor('seguridad/roles_permisos/' . $rol->ID . '/' . $offset, '<span class="glyphicon glyphicon-lock"></span>'),
                        anchor('seguridad/roles_update/' . $rol->ID . '/' . $offset, '<span class="glyphicon glyphicon-edit"></span>'),
                        anchor('seguridad/roles_delete/' . $rol->ID . '/' . $offset, '<span class="glyphicon glyphicon-remove"></span>')
                );
        }
        $data['table'] = $this->table->generate();
        $data['link_add'] = 'seguridad/roles_add/'.$offset;
        $data['titulo'] = 'Roles <small>Lista</small>';
        $data['action'] = 'seguridad/roles_lista';

        $this->load->view('lista', $data);
    }
    
    public function roles_add( $offset = 0 ) {
        $data['titulo'] = 'Roles <small>Agregar</small>';
        $data['link_back'] = 'seguridad/roles_lista/'.$offset;
        $data['mensaje'] = '';
        $data['action'] = 'seguridad/roles_add/'.$offset;
        
        if ( ($rol = $this->input->post()) ){
            $this->load->model('rol', 'r');
            
            $this->load->library('uuid');
            $rol['ID'] = $this->uuid->v4();
            
            $this->r->save($rol);
            
            $this->session->set_flashdata('mensaje',$this->config->item('create_success'));
            redirect($this->folder.$this->clase.'roles_add/'.$offset);
        }
        $this->load->view('seguridad/roles/formulario', $data);
    }
    
    public function roles_update( $id = NULL, $offset = 0 ) {
        if (empty($id)) {
            redirect('seguridad/roles_lista');
        }
        
        $this->load->model('rol','r');

        $data['titulo'] = 'Roles <small>Modificar</small>';
        $data['atributos_form'] = array('id' => 'form', 'class' => 'form-horizontal');
        $data['link_back'] = 'seguridad/roles_lista/'. $offset;

        $data['mensaje'] = '';
        $data['action'] = 'seguridad/roles_update/' . $id . '/' . $offset;

        if ( ($rol = $this->input->post()) ){
            $this->r->update($id, $rol);
            $this->session->set_flashdata('mensaje',$this->config->item('update_success'));
            redirect($this->folder.$this->clase.'roles_update/'.$id . '/' . $offset);
        }
        $data['datos'] = $this->r->get_by_id($id)->row();
        $this->load->view('seguridad/roles/formulario', $data);
    }
    
    public function roles_delete( $id = NULL, $offset = 0 ){
        if (!empty($id)) {
            $this->load->model('rol', 'r');
            $this->db->trans_start();
            $this->r->update_permisos($id, NULL);
            $resultado = $this->r->delete($id);
            $this->db->trans_complete();
            if($resultado > 0)
                $this->session->set_flashdata('mensaje',$this->config->item('update_success'));
            else
                $this->session->set_flashdata('mensaje',$this->config->item('error'));
        }
        redirect('seguridad/roles_lista/'.$offset);
    }
    
    public function roles_permisos( $id = NULL, $offset = 0 ) {

        if (empty($id)) {
            redirect('seguridad/roles_lista');
        }
        
        $this->load->model('rol','r');
        $this->load->model('permiso','p');

        $data['titulo'] = 'Roles <small>Permisos</small>';
        $data['atributos_form'] = array('id' => 'form', 'class' => 'form-horizontal');
        $data['link_back'] = 'seguridad/roles_lista/'.$offset;

        $data['mensaje'] = '';
        $data['action'] = 'seguridad/roles_permisos/' . $id. '/' . $offset;

        $data['datos'] = $this->r->get_by_id($id)->row();
        
        /* Si llegan datos por POST, se insertan en la base de datos*/
        if ( ($datos = $this->input->post()) ){
            //unset($permisos['marcar_todos']);  // Variable que se pasa por post solo para que si no se seleccionó ningún checkbox $this->input->post de TRUE
            $perms = array();
            if( ($permisos = $this->input->post('permisos')) ){
                foreach ($permisos as $permiso){
                    $perms[] = array(
                        'ROLEID' => $id,
                        'PERMID' => $permiso,
                        'VALUE' => '1'
                    );
                }
            }
            $this->r->update_permisos($id, $perms);
            $this->session->set_flashdata('mensaje',$this->config->item('update_success'));
            redirect($this->folder.$this->clase.'roles_permisos/'.$id . '/' . $offset);
        }
        
        // Obtener todos los permisos
        $permisos = $this->p->get_all()->result();
        
        // generar tabla con permisos
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array ( 'table_open'  => '<table class="' . $this->config->item('tabla_css') . '">' );
        $this->table->set_template($tmpl);
        $this->table->set_heading('Menú','Acción', 'Ruta', 'Activo');
        foreach ($permisos as $permiso) {
            $this->table->add_row(
                    strtoupper($permiso->FOLDER), 
                    $permiso->PERMNAME, 
                    $permiso->PERMKEY,
                    '<input type="checkbox" name="permisos[]" value="'.$permiso->ID.'" '.($this->r->get_permiso_by_id($permiso->ID, $id)->num_rows() > 0 ? 'checked' : '').'/>'
            );
        }
        $data['table'] = $this->table->generate();
        
        $this->load->view('seguridad/roles/permisos', $data);

    }
    
    
    public function usuarios_lista( $offset = 0 ){
        
        $this->load->model('usuario','u');
        $this->load->model('rol','r');
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        // obtener datos
        $this->config->load("pagination");
        $page_limit = $this->config->item("per_page");
        $usuarios = $this->u->get_paged_list($page_limit, $offset, $filtro)->result();
        
        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url('seguridad/usuarios_lista/');
        $config['total_rows'] = $this->u->count_all( $filtro );
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        
        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array ( 'table_open'  => '<table class="' . $this->config->item('tabla_css') . '">' );
        $this->table->set_template($tmpl);
        $this->table->set_heading('Nombre', 'Rol', 'Activo', '', '', '', '');
        
        foreach ($usuarios as $usuario) {
            $rol = $this->r->get_by_id($usuario->ROLE)->row();
                $this->table->add_row(
                        $usuario->NAME,
                        $rol->NAME,
                        ord($usuario->VISIBLE) === 1 ? '<span class="glyphicon glyphicon-ok"></span>' : '',
                        anchor('seguridad/usuarios_permisos/' . $usuario->ID. '/' . $offset, '<span class="glyphicon glyphicon-lock"></span>'),
                        anchor('seguridad/usuarios_roles/' . $usuario->ID. '/' . $offset, '<span class="glyphicon glyphicon-user"></span>'),
                        anchor('seguridad/usuarios_update/' . $usuario->ID. '/' . $offset, '<span class="glyphicon glyphicon-edit"></span>'),
                        anchor('seguridad/usuarios_delete/' . $usuario->ID. '/' . $offset, '<span class="glyphicon glyphicon-remove"></span>')
                );
        }
        
        $data['table'] = $this->table->generate();
        $data['link_add'] = 'seguridad/usuarios_add';
        $data['titulo'] = 'Usuarios <small>Lista</small>';
        $data['action'] = 'seguridad/usuarios_lista';

        $this->load->view('lista', $data);
    }
    
    public function usuarios_update( $id = NULL, $offset = 0 ) {
        if (empty($id)) {
            redirect('seguridad/usuarios_lista');
        }
        
        $this->load->model('usuario','u');
        $this->load->model('rol','r');

        $data['titulo'] = 'Usuarios <small>Modificar</small>';
        $data['atributos_form'] = array('id' => 'form', 'class' => 'form-horizontal');
        $data['link_back'] = 'seguridad/usuarios_lista/'.$offset;

        $data['mensaje'] = '';
        $data['action'] = 'seguridad/usuarios_update/' . $id . '/' .$offset;

        if ( ($usuario = $this->input->post()) ){
            if(strlen($usuario['APPPASSWORD']) > 0){
                $usuario['APPPASSWORD'] = 'sha1:'.sha1($usuario['APPPASSWORD']);
            }else{
                unset($usuario['APPPASWORD']);
                unset($usuario['confirmar_password']);
            }
            
            if(!isset($usuario['VISIBLE']))
                $usuario['VISIBLE'] = FALSE;
            
            $this->u->update($id, $usuario);
            $this->session->set_flashdata('mensaje',$this->config->item('update_success'));
            redirect($this->folder.$this->clase.'usuarios_update/'.$id . '/' . $offset);
        }
        $data['datos'] = $this->u->get_by_id($id)->row();
        $data['roles'] = $this->r->get_all()->result();
        $this->load->view('seguridad/usuarios/formulario', $data);
    }
    
    public function usuarios_add( $offset = 0 ) {
        $this->load->model('rol','r');
        
        $data['titulo'] = 'Usuarios <small>Agregar</small>';
        $data['atributos_form'] = array('id' => 'form', 'class' => 'form-horizontal');
        $data['link_back'] = 'seguridad/usuarios_lista/'.$offset;
        $data['mensaje'] = '';
        $data['action'] = 'seguridad/usuarios_add/'.$offset;
        
        if ( ($usuario = $this->input->post()) ){
            $this->load->model('usuario', 'u');
            $this->load->library('uuid');
            
            if(strlen($usuario['APPPASSWORD']) > 0){
                $usuario['APPPASSWORD'] = 'sha1:'.sha1($usuario['APPPASSWORD']);
            }
            unset($usuario['confirmar_password']);
            
            $usuario['ID'] = $this->uuid->v4();
            $this->u->save($usuario);
            
            $this->session->set_flashdata('mensaje',$this->config->item('create_success'));
            redirect($this->folder.$this->clase.'usuarios_add/'.$offset);
        }
        $data['roles'] = $this->r->get_all()->result();
        $this->load->view('seguridad/usuarios/formulario', $data);
    }
    
    public function usuarios_delete( $id = NULL, $offset = 0 ){
        if (!empty($id)) {
            $this->load->model('usuario', 'u');
            $this->db->trans_start();
            $this->u->update_roles($id, NULL);
            $this->u->update_permisos($id, NULL);
            $resultado = $this->u->delete($id);
            $this->db->trans_complete();
            if($resultado > 0)
                $this->session->set_flashdata('mensaje',$this->config->item('update_success'));
            else
                $this->session->set_flashdata('mensaje',$this->config->item('error'));
            
        }
        redirect('seguridad/usuarios_lista/'.$offset);
    }
    
    public function usuarios_permisos( $id = NULL, $offset = 0 ) {

        if (empty($id)) {
                redirect('seguridad/usuarios_lista');
        }
        
        $this->load->model('usuario','u');
        $this->load->model('permiso','p');

        $data['titulo'] = 'Usuarios <small>Permisos</small>';
        $data['atributos_form'] = array('id' => 'form', 'class' => 'form-horizontal');
        $data['link_back'] = 'seguridad/usuarios_lista/'.$offset;

        $data['mensaje'] = '';
        $data['action'] = 'seguridad/usuarios_permisos/' . $id . '/' .$offset;

        $usuario = $this->u->get_by_id($id)->row();
        $data['datos'] = $usuario;
        
        /* Si llegan datos por POST, se insertan en la base de datos*/
        if ($this->input->post()) {
            $perms = array();
            if( ($permisos = $this->input->post('permisos')) ){
                foreach ($permisos as $permiso){
                    $perms[] = array(
                        'USERID' => $id,
                        'PERMID' => $permiso,
                        'VALUE' => '1'
                    );
                }
            }
            $this->u->update_permisos($id, $perms);
            $this->session->set_flashdata('mensaje',$this->config->item('update_success'));
            redirect($this->folder.$this->clase.'usuarios_permisos/'.$id . '/' . $offset);
        }
        
        // Obtener todos los permisos
        $permisos = $this->p->get_all()->result();
        
        // generar tabla con permisos
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array ( 'table_open'  => '<table class="' . $this->config->item('tabla_css') . '">' );
        $this->table->set_template($tmpl);
        $this->table->set_heading('Menú','Acción', 'Ruta', 'Activo');
        foreach ($permisos as $permiso) {
            $this->table->add_row(
                    strtoupper($permiso->FOLDER), 
                    $permiso->PERMNAME, 
                    $permiso->PERMKEY,
                    '<input type="checkbox" name="permisos[]" value="'.$permiso->ID.'" '.($this->u->get_permiso_by_id($permiso->ID, $id)->num_rows() > 0 ? 'checked' : '').'/>'
            );
        }
        $data['table'] = $this->table->generate();
        
        $this->load->view('seguridad/usuarios/permisos_roles', $data);

    }
    
    public function usuarios_roles( $id = NULL, $offset = 0 ) {

        if (empty($id)) {
                redirect('seguridad/usuarios_lista');
        }
        
        $this->load->model('usuario','u');
        $this->load->model('rol','r');

        $data['titulo'] = 'Usuarios <small>Roles</small>';
        $data['atributos_form'] = array('id' => 'form', 'class' => 'form-horizontal');
        $data['link_back'] = 'seguridad/usuarios_lista/'.$offset;

        $data['mensaje'] = '';
        $data['action'] = 'seguridad/usuarios_roles/' . $id . '/' .$offset;

        /* Si llegan datos por POST, se insertan en la base de datos*/
        if ($this->input->post()) {
            $roles = array();
            if($this->input->post('roles')){
                foreach ($this->input->post('roles') as $rol){
                    $roles[] = array(
                        'USERID' => $id,
                        'ROLEID' => $rol
                    );
                }
            }
            $this->u->update_roles($id, $roles);
            $this->session->set_flashdata('mensaje',$this->config->item('update_success'));
            redirect($this->folder.$this->clase.'usuarios_roles/'.$id . '/' . $offset);
        }
        
        $usuario = $this->u->get_by_id($id)->row();
        $data['datos'] = $usuario;
        
        // Obtener todos los permisos
        $roles = $this->r->get_all()->result();
        
        // generar tabla con permisos
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array ( 'table_open'  => '<table class="' . $this->config->item('tabla_css') . '">' );
        $this->table->set_template($tmpl);
        $this->table->set_heading('Nombre', 'Descripción');
        foreach ($roles as $rol) {
            $this->table->add_row(
                    $rol->NAME, 
                    $rol->DESCRIPTION, 
                    '<input type="checkbox" name="roles[]" value="'.$rol->ID.'" '.($this->u->get_rol_by_id($rol->ID, $id)->num_rows() > 0 ? 'checked' : '').'/>'
            );
        }
        $data['table'] = $this->table->generate();
        
        $this->load->view('seguridad/usuarios/permisos_roles', $data);

    }
    
    public function usuarios_password( $id = NULL ) {

        $this->load->model('usuario','u');
        
        if(empty($id))
            $id = $this->session->userdata('id_usuario');

        $data['titulo'] = 'Usuario <small>Cambiar contraseña</small>';
        $data['atributos_form'] = array('id' => 'form', 'class' => 'form-horizontal');
        $data['link_back'] = 'home';

        $data['mensaje'] = '';
        $data['action'] = 'seguridad/usuarios_password/' . $id;

        if ( ($datos = $this->input->post()) ) {
            $usuario = array('APPPASSWORD' => 'sha1:'.sha1($datos['APPPASSWORD']));
            $this->u->update($id, $usuario);
            $this->session->set_flashdata('mensaje',$this->config->item('update_success'));
            redirect($this->folder.$this->clase.'usuarios_password/'.$id);
        }

        $data['datos'] = $this->u->get_by_id($id)->row();
        $this->load->view('seguridad/usuarios/formulario_password', $data);
    }
}

?>