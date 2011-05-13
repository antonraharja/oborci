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

	private function _get_crud() {
		$data = array(
			'insert' => array(
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
					'confirm' => TRUE,
					'mandatory' => TRUE,
					'confirm_label' => t('Confirm password'),
				),
			),
			'select' => array(
				array(
					'name' => 'id',
					'label' => 'ID',
					'key' => TRUE,
					'hidden' => TRUE,
				),
				array(
					'name' => 'username',
					'label' => 'Username',
				),
			),
			'update' => array(
				array (
					'name' => 'username',
					'label' => t('Username'),
					'type' => 'input',
					'show_value' => TRUE,
					'disabled' => TRUE,
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
			'properties' => array(
				'datasource' => 'sc_users',
				'name' => 'users',
				'uri' => 'users/index',
				'index_column' => TRUE,
				'index_column_start' => 1,
				'pagination_per_page' => 2,
				'insert' => TRUE,
				'update' => TRUE,
				'delete' => TRUE,
				'crud_title' => '<h1>User Management</h1>',
				'crud_form_title' => '<h2>List of Users</h2>',
				'insert_form_title' => '<h2>Insert Data</h2>',
				'update_form_title' => '<h2>Update Data</h2>',
				'delete_form_title' => '<h2>Delete Data</h2>',
			),
		);
		$this->crud->set_data($data);
		return $this->crud->render();
	}
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */
