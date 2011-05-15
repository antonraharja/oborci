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

	private function _get_role_names() {
		$returns = NULL;
		$query = $this->db->get('sc_roles');
		foreach ($query->result() as $row) {
			$returns[$row->id] = $row->name;
		}	
		return $returns;
	}
	
	private function _get_crud() {
		$data = array(
			'insert' => array(
				array (
					'name' => 'role_id',
					'label' => t('Role Name'),
					'type' => 'dropdown',
					'options' => $this->_get_role_names(),
					'mandatory' => TRUE,
				),
				array (
					'name' => 'username',
					'label' => t('Username'),
					'type' => 'input',
					'unique' => TRUE,
					'mandatory' => TRUE,
				),
				array(
					'name' => 'password',
					'label' => t('Password'),
					'type' => 'password',
					'mandatory' => TRUE,
					'confirm' => TRUE,
					'confirm_label' => t('Confirm password'),
				),
			),
			'select' => array(
				array(
					'name' => 'id',
					'table' => 'sc_users',
					'label' => 'ID',
					'key' => TRUE,
					'hidden' => TRUE,
				),
				array(
					'name' => 'name',
					'table' => 'sc_roles',
					'label' => 'Role Name',
				),
				array(
					'name' => 'username',
					'table' => 'sc_users',
					'label' => 'Username',
				),
			),
			'update' => array(
				array (
					'name' => 'role_id',
					'label' => t('Role Name'),
					'type' => 'dropdown',
					'options' => $this->_get_role_names(),
					'mandatory' => TRUE,
				),
				array (
					'name' => 'username',
					'label' => t('Username'),
					'type' => 'input',
					'readonly' => TRUE,
				),
				array(
					'name' => 'password',
					'label' => t('Password'),
					'type' => 'password',
					'confirm' => TRUE,
					'confirm_label' => t('Confirm password'),
				),
			),
			'delete' => array(
				array (
					'name' => 'username',
					'label' => t('Username'),
				),
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
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */
