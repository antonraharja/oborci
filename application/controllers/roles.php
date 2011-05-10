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
			$data['crud'] = $this->_get_crud($param);
			$this->load->view('roles_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}

	private function _get_crud($param=NULL) {		
		$data = array(
			'insert' => array(
				0 => array (
					'field' => 'name',
					'title' => _('Role name'),
					'type' => 'text',
					'size' => 50,
					'maxlength' => 50,
					'unique' => TRUE,
					'readonly' => FALSE 
				)
			),
			'select' => array(
				0 => array(
					'field' => 'id',
					'title' => 'ID'
				),
				1 => array(
					'field' => 'name',
					'title' => 'Role name'
				)
			),
			'update' => array(
				0 => array(
					'field' => 'name',
					'title' => _('Role name'),
					'type' => 'text',
					'size' => 50,
					'maxlength' => 50,
					'unique' => TRUE,
					'readonly' => FALSE
				)
			),
			'delete' => array(
				0 => array(
					'field' => 'id'
				)
			),
			'datasource' => array(
				'source' => 'table',
				'name' => 'sc_roles'
			),
			'action' => array(
				'insert' => TRUE,
				'update' => TRUE,
				'delete' => TRUE
			)
		);
		$this->load->library('Crud');
		$this->crud->set_name('roles');
		$this->crud->set_grid($data);
		$this->crud->set_uri('index');
		return $this->crud->render();
	}

}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */
