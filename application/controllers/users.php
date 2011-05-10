<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Users controller
 *
 * @property SC_ACL $SC_ACL
 * @property SC_auth $SC_auth
 * @property SC_template $SC_template
 *
 * @author Anton Raharja
 *
 */
class Users extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('SC_ACL', 'SC_auth', 'SC_template'));
		$this->SC_ACL->validate();
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index($param=NULL) {
		if ($this->SC_ACL->get_access()) {
			$data['menu']['box'] = $this->SC_template->menu_box();
			$data['login'] = $this->SC_auth->get_login();
			$data['crud'] = $this->_get_crud();
			$this->load->view('users_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}

	private function _get_crud() {
		$data = array(
			'insert' => array(
				0 => array (
					'field' => 'username',
					'title' => _('Username'),
					'type' => 'text',
					'size' => 50,
					'maxlength' => 20,
					'unique' => TRUE,
					'readonly' => FALSE 
				),
				1 => array(
					'field' => 'password',
					'title' => _('Password'),
					'type' => 'password',
					'size' => 50,
					'maxlength' => 50,
					'confirm' => TRUE,
					'confirm_title' => _('Confirm password'),
					'readonly' => FALSE
				)
			),
			'select' => array(
				0 => array(
					'field' => 'id',
					'title' => 'ID'
				),
				1 => array(
					'field' => 'username',
					'title' => 'Username'
				)
			),
			'update' => array(
				0 => array (
					'field' => 'username',
					'title' => _('Username'),
					'type' => 'text',
					'size' => 50,
					'readonly' => TRUE 
				),
				1 => array(
					'field' => 'password',
					'title' => _('Password'),
					'type' => 'password',
					'size' => 50,
					'maxlength' => 50,
					'confirm' => TRUE,
					'confirm_title' => _('Confirm password'),
					'readonly' => FALSE
				)
			),
			'delete' => array(
				0 => array(
					'field' => 'id',
					'confirm' => TRUE
				)
			),
			'datasource' => array(
				'source' => 'table',
				'name' => 'sc_users'
			),
			'properties' => array(
				'uri' => 'index',
				'index_column' => TRUE,
				'index_column_start' => 1,
				'insert' => TRUE,
				'update' => TRUE,
				'delete' => TRUE
			)
		);
		$this->load->library('Crud');
		$this->crud->set_data($data);
		return $this->crud->render();
	}
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */
