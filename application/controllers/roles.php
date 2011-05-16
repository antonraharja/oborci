<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Roles controller
 *
 * @property SC_auth $SC_auth
 * @property crud $crud
 * @property template $template
 *
 * @author Anton Raharja
 *
 */
class Roles extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('speedcoding/SC_auth', 'template'));
		$this->load->library('speedcoding/Crud');
		$this->SC_auth->validate();
	}

	/**
	 * Helper function which creates grid for CRUD service
	 * @return string HTML of CRUD grid
	 */
	private function _get_crud_for_index() {		
		$data = array(
			'insert' => array(
				array ('name' => 'name', 'label' => t('Role name'),	'type' => 'input',
					'rules' => array('unique', 'required', 'max_length' => 30, 'min_length' => 3, 'trim', 'xss_clean'),),
			),
			'select' => array(
				array('name' => 'id', 'label' => 'ID', 'rules' => array('key', 'hidden', 'trim', 'htmlspecialchars'),),
				array('name' => 'name',	'label' => t('Role name'), 'link' => 'roles/members/{id}', 
					'rules' => array('key', 'hidden', 'trim', 'htmlspecialchars'),),
			),
			'update' => array(
				array('name' => 'name',	'label' => t('Role name'), 'type' => 'input', 
					'rules' => array('unique', 'required', 'max_length' => 30, 'min_length' => 3, 'trim', 'xss_clean'),),
			),
			'delete' => array(
				array('name' => 'name', 'label' => t('Role name'),),
			),
			'datasource' => array(
				'table' => 'sc_roles',
			),
			'properties' => array(
				'name' => 'roles',
				'uri' => 'roles/index',
				'index_column' => TRUE,
				'index_column_start' => 1,
				'pagination_per_page' => 2,
				'insert' => TRUE,
				'update' => TRUE,
				'delete' => TRUE,
				'crud_title' => '<h1>'.t('Role Management').'</h1>',
				'crud_form_title' => '<h2>'.t('List of Roles').'</h2>',
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
			$data['crud'] = $this->_get_crud_for_index();
			$this->load->view('roles_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}
	
	public function members($param=NULL) {
		
	}

}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */
