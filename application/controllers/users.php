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
		$this->load->library('Crud');
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
				array (
					'name' => 'username',
					'label' => _('Username'),
					'type' => 'input',
					'unique' => TRUE,
				),
				array(
					'name' => 'password',
					'label' => _('Password'),
					'type' => 'password',
					'confirm' => TRUE,
					'confirm_label' => _('Confirm password'),
				),
			),
			'select' => array(
				array(
					'name' => 'id',
					'label' => 'ID',
					'key' => TRUE,
				),
				array(
					'name' => 'username',
					'label' => 'Username',
				),
			),
			'update' => array(
				array (
					'name' => 'username',
					'label' => _('Username'),
					'type' => 'input',
					'value' => '@username',
					'disabled' => TRUE,
				),
				array(
					'name' => 'password',
					'label' => _('Password'),
					'type' => 'password',
					'confirm' => TRUE,
					'confirm_label' => _('Confirm password'),
				),
			),
			'delete' => array(
				array (
					'name' => 'username',
					'label' => _('Username'),
					'type' => 'input',
					'value' => '@username',
					'disabled' => TRUE,
				),
			),
			'properties' => array(
				'datasource' => 'sc_users',
				'name' => 'users',
				'uri' => 'users',
				'index_column' => TRUE,
				'index_column_start' => 1,
				'insert' => TRUE,
				'update' => TRUE,
				'delete' => TRUE,
			),
		);
		$this->crud->set_data($data);
		return $this->crud->render();
	}
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */
