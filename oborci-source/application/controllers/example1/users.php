<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Users controller
 *
 * @property oci_auth $oci_auth
 * @property themes $themes
 *
 * @author Anton Raharja
 *
 */
class Users extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('oborci/oci_auth', 'example1/themes'));
                $this->load->library(array('oborci/Crud'));
		$this->oci_auth->validate();
	}

	/**
	 * Helper function to get role names
	 * @return string $returns HTML role names
	 */
	private function _get_role_names() {
		$returns = NULL;
		$query = $this->db->get('oci_roles');
		foreach ($query->result() as $row) {
			$returns[$row->id] = $row->name;
		}	
		return $returns;
	}
	
	/**
	 * Helper function which creates grid for CRUD service
	 * @return string HTML of CRUD grid
	 */
	private function _get_crud() {
		$data = array(
			'insert' => array(
				array ('name' => 'role_id', 'label' => t('Role Name'), 'type' => 'dropdown', 'options' => $this->_get_role_names(),
					'rules' => array('required'),),
				array ('name' => 'username', 'label' => t('Username'), 'type' => 'input',
					'rules' => array('unique', 'required', array('max_length' => 30), array('min_length' => 6), 'trim'),),
				array('name' => 'password', 'label' => t('Password'), 'type' => 'password', 'confirm_label' => t('Confirm password'),
					'rules' => array('confirm', 'required', array('max_length' => 30), array('min_length' => 6), 'trim'),),
			),
			'select' => array(
				array('name' => 'id', 'table' => 'oci_users', 'label' => 'ID', 
					'rules' => array('key', 'hidden', 'trim'),),
				array('name' => 'role_id', 'table' => 'oci_users', 'label' => 'Role ID', 
					'rules' => array('hidden', 'trim'),),
				array('name' => 'name',	'table' => 'oci_roles', 'label' => 'Role Name', 'link' => 'example1/roles/members/{role_id}',
					'rules' => array('trim', 'htmlspecialchars'),),
				array('name' => 'username', 'table' => 'oci_users', 'label' => 'Username', 'link' => 'example1/preference/show/{id}',
					'rules' => array('trim',  'htmlspecialchars'),),
			),
			'update' => array(
				array ('name' => 'role_id', 'label' => t('Role Name'), 'type' => 'dropdown', 'options' => $this->_get_role_names(),
					'rules' => array('required'),),
				array ('name' => 'username', 'label' => t('Username'), 'type' => 'input',
					'rules' => array('readonly'),),
				array('name' => 'password', 'label' => t('Password'), 'type' => 'password', 'confirm_label' => t('Confirm password'),
					'rules' => array('confirm', array('max_length' => 30), array('min_length' => 6), 'trim'),),
			),
			'delete' => array(
				array ('name' => 'username', 'label' => t('Username'),),
			),
                        'search' => array('oci_users.id' => 'ID', 'oci_users.username' => t('Username')),
			'datasource' => array(
				'table' => 'oci_users',
				'join_table' => 'oci_roles',
				'join_type' => 'left',
				'join_param' => 'oci_users.role_id = oci_roles.id',
			),
			'properties' => array(
				'name' => 'users',
				'uri' => 'example1/users/index',
				'index_column' => TRUE,
				'index_column_start' => 1,
                                'pagination' => FALSE,
				'pagination_per_page' => 5,
				'insert' => TRUE,
				'update' => TRUE,
				'delete' => TRUE,
				'crud_title' => NULL,
				'crud_form_title' => '<h2>'.t('List of Users').'</h2>',
				'insert_form_title' => '<h2>'.t('Insert Data').'</h2>',
				'update_form_title' => '<h2>'.t('Update Data').'</h2>',
				'delete_form_title' => '<h2>'.t('Delete Data').'</h2>',
			),
		);
		$this->crud->set_data($data);
		$returns = $this->crud->render();
                return $returns;
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index($param=NULL) {
		if ($this->oci_auth->get_access()) {
			$data['menu']['box'] = $this->themes->menu_box();
			$data['login'] = $this->themes->get_login();
			$data['crud'] = $this->_get_crud();
                        // print_r($data); die();
			$this->load->view('example1/users_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}
	
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */
