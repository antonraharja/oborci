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
				array('name' => 'id', 'label' => 'ID', 'rules' => array('key', 'hidden', 'trim'),),
				array('name' => 'name',	'label' => t('Role name'), 'link' => 'roles/members/{id}', 
					'rules' => array('trim', 'htmlspecialchars'),),
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

	private function _get_roles_members($param=NULL) {
		$data = array(
			'select' => array(
				array('name' => 'id', 'label' => 'ID', 
					'rules' => array('key', 'hidden', 'trim'),),
				array('name' => 'username', 'label' => 'Username', 'link' => 'preference/view/{id}',
					'rules' => array('trim',  'htmlspecialchars'),),
			),
			'datasource' => array(
				'table' => 'sc_users',
				//'where' => array('role_id' => $param),
			),
			'properties' => array(
				'name' => 'roles_members',
				'uri' => 'roles/members/index',
				'index_column' => TRUE,
				'index_column_start' => 1,
				'pagination_per_page' => 2,
				'crud_title' => '<h1>'.t('Members List').'</h1>',
				'crud_form_title' => '<h2>'.t('List of Members').'</h2>',
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
		if ($this->SC_auth->get_access()) {
			$data['menu']['box'] = $this->template->menu_box();
			$data['login'] = $this->template->get_login();
			$data['members_list'] = $this->_get_roles_members($param);
			$this->load->view('roles_members_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}

}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */
