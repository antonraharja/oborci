<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Users controller
 *
 * @property SC_auth $SC_auth
 * @property template $template
 *
 * @author Anton Raharja
 *
 */
class Users extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('speedcoding/SC_auth', 'template'));
		$this->load->library('speedcoding/Crud');
		$this->SC_auth->validate();
	}

	/**
	 * Helper function to get role names
	 * @return string $returns HTML role names
	 */
	private function _get_role_names() {
		$returns = NULL;
		$query = $this->db->get('sc_roles');
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
					'rules' => array('unique', 'required', 'max_length' => 30, 'min_length' => 6, 'trim', 'xss_clean'),),
				array('name' => 'password', 'label' => t('Password'), 'type' => 'password',	'confirm_label' => t('Confirm password'),
					'rules' => array('confirm', 'required', 'max_length' => 30, 'min_length' => 6, 'trim', 'xss_clean'),),
			),
			'select' => array(
				array('name' => 'id', 'table' => 'sc_users', 'label' => 'ID', 
					'rules' => array('key', 'hidden', 'trim'),),
				array('name' => 'name',	'table' => 'sc_roles', 'label' => 'Role Name', 'link' => 'roles/members/{id}',
					'rules' => array('trim', 'htmlspecialchars'),),
				array('name' => 'username', 'table' => 'sc_users', 'label' => 'Username', 'link' => 'preference/view/{id}',
					'rules' => array('trim',  'htmlspecialchars'),),
			),
			'update' => array(
				array ('name' => 'role_id', 'label' => t('Role Name'), 'type' => 'dropdown', 'options' => $this->_get_role_names(),
					'rules' => array('required'),),
				array ('name' => 'username', 'label' => t('Username'), 'type' => 'input',
					'rules' => array('readonly'),),
				array('name' => 'password',	'label' => t('Password'), 'type' => 'password', 'confirm_label' => t('Confirm password'),
					'rules' => array('confirm', 'max_length' => 30, 'min_length' => 6, 'trim', 'xss_clean'),),
			),
			'delete' => array(
				array ('name' => 'username', 'label' => t('Username'),),
			),
			'datasource' => array(
				'table' => 'sc_users',
				'join_table' => 'sc_roles',
				'join_type' => 'left',
				'join_param' => 'sc_users.role_id = sc_roles.id',
			),
			'properties' => array(
				'name' => 'users',
				'uri' => 'users/index',
				'index_column' => TRUE,
				'index_column_start' => 1,
				'pagination_per_page' => 2,
				'insert' => TRUE,
				'update' => TRUE,
				'delete' => TRUE,
				'crud_title' => '<h1>'.t('User Management').'</h1>',
				'crud_form_title' => '<h2>'.t('List of Users').'</h2>',
				'insert_form_title' => '<h2>'.t('Insert Data').'</h2>',
				'update_form_title' => '<h2>'.t('Update Data').'</h2>',
				'delete_form_title' => '<h2>'.t('Delete Data').'</h2>',
			),
		);
		$this->crud->set_data($data);
		return $this->crud->render();
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index($param=NULL) {
		if ($this->SC_auth->get_access()) {
			$data['menu']['box'] = $this->template->menu_box();
			$data['login'] = $this->template->get_login();
			$data['crud'] = $this->_get_crud();
			$this->load->view('users_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}
	
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */
