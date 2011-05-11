<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Roles controller
 *
 * @property SC_ACL $SC_ACL
 * @property SC_auth $SC_auth
 * @property SC_template $SC_template
 * @property crud $crud
 *
 * @author Anton Raharja
 *
 */
class Roles extends CI_Controller {

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
	public function index() {
		if ($this->SC_ACL->get_access()) {
			$data['menu']['box'] = $this->SC_template->menu_box();
			$data['login'] = $this->SC_auth->get_login();
			$data['crud'] = $this->_get_crud();
			$this->load->view('roles_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}

	private function _get_crud() {		
		$data = array(
			'insert' => array(
				array (
					'name' => 'name',
					'label' => _('Role name'),
					'type' => 'input',
					'unique' => TRUE,
				),
			),
			'select' => array(
				array(
					'name' => 'id',
					'label' => 'ID',
					'key' => TRUE,
				),
				array(
					'name' => 'name',
					'label' => _('Role name'),
				),
			),
			'update' => array(
				array(
					'name' => 'name',
					'label' => _('Role name'),
					'type' => 'input',
					'unique' => TRUE,
					'value' => '@name',
					'disabled' => TRUE,
				),
			),
			'delete' => array(
				array(
					'name' => 'name',
					'label' => _('Role name'),
					'type' => 'input',
					'unique' => TRUE,
					'value' => '@name',
					'disabled' => TRUE,
				),
			),
			'properties' => array(
				'datasource' => 'sc_roles',
				'name' => 'roles',
				'uri' => 'roles',
				'insert' => TRUE,
				'update' => TRUE,
				'delete' => TRUE,
			),
		);
		$this->crud->set_data($data);
		return $this->crud->render();
	}

}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */
