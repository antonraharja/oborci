<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Roles controller
 *
 * @property oci_auth $oci_auth
 * @property crud $crud
 * @property oci_template $oci_template
 *
 * @author Anton Raharja
 *
 */
class Roles extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('oborci/oci_auth', 'oborci/oci_template'));
                $this->load->library(array('oborci/Crud'));
		$this->oci_auth->validate();
	}

	/**
	 * Helper function which creates grid for CRUD service on index page
	 * @return string HTML of CRUD grid
	 */
	private function _get_crud_for_index() {		
		$data = array(
			'insert' => array(
				array ('name' => 'name', 'label' => t('Role name'), 'type' => 'input',
					'rules' => array('unique', 'required', array('max_length' => 30, 'min_length' => 3), 'trim'),),
			),
			'select' => array(
				array('name' => 'id', 'label' => 'ID', 'rules' => array('key', 'hidden', 'trim'),),
				array('name' => 'name',	'label' => t('Role name'), 'link' => 'roles/members/{id}', 
					'rules' => array('trim', 'htmlspecialchars'),),
			),
			'update' => array(
				array('name' => 'name',	'label' => t('Role name'), 'type' => 'input', 
					'rules' => array('unique', 'required', array('max_length' => 30, 'min_length' => 3), 'trim'),),
			),
			'delete' => array(
				array('name' => 'name', 'label' => t('Role name'),),
			),
                        'search' => array('name' => t('Role name')),
			'datasource' => array(
				'table' => 'oci_roles',
			),
			'properties' => array(
				'name' => 'roles',
				'uri' => 'roles/index',
				'index_column' => TRUE,
				'index_column_start' => 1,
                                'pagination' => TRUE,
				'pagination_per_page' => 5,
				'insert' => TRUE,
				'update' => TRUE,
				'delete' => TRUE,
				'crud_title' => NULL,
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
	 * Helper function which creates grid for CRUD service on members page
	 * @return string HTML of CRUD grid
	 */
	private function _get_crud_for_members($role_id=NULL) {
		$data = array(
			'select' => array(
				array('name' => 'id', 'label' => 'ID', 
					'rules' => array('key', 'hidden', 'trim'),),
				array('name' => 'username', 'label' => 'Username', 'link' => 'preference/show/{id}',
					'rules' => array('trim',  'htmlspecialchars'),),
			),
                        'search' => array('id', 'username'),
			'datasource' => array(
				'table' => 'oci_users',
				'where' => array('role_id' => $role_id),
			),
			'properties' => array(
				'name' => 'roles_members',
				'uri' => 'roles/members/'.$role_id,
				'index_column' => TRUE,
				'index_column_start' => 1,
                                'pagination' => FALSE,
				'pagination_per_page' => 5,
				'crud_title' => NULL,
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
	 * Index page
	 * @param array $param Parameters
	 */
	public function index($param=NULL) {
		if ($this->oci_auth->get_access()) {
			$data['menu']['box'] = $this->oci_template->menu_box();
			$data['login'] = $this->oci_template->get_login();
			$data['crud'] = $this->_get_crud_for_index();
			$this->load->view('roles_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}
	
	/**
         * Members page
         * @param array $param Parameters 
         */
        public function members($param=NULL) {
		if ($this->oci_auth->get_access()) {
			$data['menu']['box'] = $this->oci_template->menu_box();
			$data['login'] = $this->oci_template->get_login();
			$data['crud'] = $this->_get_crud_for_members($param);
			$this->load->view('roles_members_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}

}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */
