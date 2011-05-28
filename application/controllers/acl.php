<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * ACL controller
 *
 * @property oci_auth $oci_auth
 * @property crud $crud
 * @property oci_themes $oci_themes
 * @property oci_roles $oci_roles
 *
 * @author Anton Raharja
 *
 */
class Acl extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('oborci/oci_auth', 'oborci/oci_themes', 'oborci/oci_screens'));
                $this->load->library(array('oborci/Crud'));
		$this->oci_auth->validate();
	}

	/**
	 * Helper function which creates grid for CRUD service
	 * @return string HTML of CRUD grid
	 */
	private function _get_crud_for_screens($role_id) {		
		$data = array(
			'insert' => array(
                                array ('name' => 'role_id', 'label' => t('Role'), 'type' => 'input', 'rules' => array('required')),
                                array ('name' => 'screen_id', 'label' => t('Screen'), 'type' => 'input', 'rules' => array('required')),
			),
			'select' => array(
				array('name' => 'id', 'table' => 'oci_roles_screens', 'label' => 'ID', 'rules' => array('key', 'hidden'),),
                                array ('name' => 'name', 'label' => t('Screen')),
                                array ('name' => 'uri', 'label' => t('URI')),
			),
			'update' => array(
                                array ('name' => 'role_id', 'label' => t('Role'), 'type' => 'input', 'rules' => array('required')),
                                array ('name' => 'screen_id', 'label' => t('Screen'), 'type' => 'input', 'rules' => array('required')),
			),
			'delete' => array(
                                array ('name' => 'role_id', 'label' => t('Role'), 'type' => 'input', 'rules' => array('required')),
                                array ('name' => 'screen_id', 'label' => t('Screen'), 'type' => 'input', 'rules' => array('required')),
			),
			'datasource' => array(
				'table' => 'oci_roles_screens',
                                'join_table' => 'oci_screens',
                                'join_type' => 'left',
                                'join_param' => 'oci_roles_screens.screen_id=oci_screens.id',
                                'where' => array('oci_roles_screens.role_id' => $role_id),
			),
			'properties' => array(
				'name' => 'screens',
				'uri' => 'acl/screens/'.$role_id,
				'index_column' => TRUE,
				'index_column_start' => 1,
				'pagination_per_page' => 10,
				'insert' => TRUE,
				'update' => TRUE,
				'delete' => TRUE,
				'crud_form_title' => '<h2>'.t('List of screens').'</h2>',
				'insert_form_title' => '<h2>'.t('Insert Data').'</h2>',
				'update_form_title' => '<h2>'.t('Update Data').'</h2>',
				'delete_form_title' => '<h2>'.t('Delete Data').'</h2>',
			),
		);
		$this->crud->set_data($data);
		return $this->crud->render();
	}

	/**
	 * Screens Page for this controller.
	 * 
	 */
	public function screens($param=NULL) {
		if ($this->oci_auth->get_access()) {
			$data['menu']['box'] = $this->oci_themes->menu_box();
			$data['login'] = $this->oci_themes->get_login();
			$data['crud'] = $this->_get_crud_for_screens($param);
			$this->load->view('acl_screens_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}
	
	/**
	 * Menus Page for this controller.
	 * 
	 */
	public function menus($param=NULL) {
		if ($this->oci_auth->get_access()) {
			$data['menu']['box'] = $this->oci_themes->menu_box();
			$data['login'] = $this->oci_themes->get_login();
			$data['crud'] = $this->_get_crud_for_menus();
			$this->load->view('acl_menus_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}
}

/* End of file acl.php */
/* Location: ./application/controllers/acl.php */
