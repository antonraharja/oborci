<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Screens controller
 *
 * @property auth $auth
 * @property crud $crud
 * @property template $template
 *
 * @author Anton Raharja
 *
 */
class Screens extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->library(array('oborci/Auth', 'oborci/Crud', 'oborci/Template'));
		$this->auth->validate();
	}

	/**
	 * Helper function which creates grid for CRUD service
	 * @return string HTML of CRUD grid
	 */
	private function _get_crud_for_index() {		
		$data = array(
			'insert' => array(
                                array ('name' => 'name', 'label' => t('Screen name'), 'type' => 'input', 'rules' => array('required')),
                                array ('name' => 'uri', 'label' => t('URI'), 'type' => 'input', 'rules' => array('required')),
			),
			'select' => array(
				array('name' => 'id', 'label' => 'ID', 'rules' => array('key', 'hidden'),),
                                array ('name' => 'name', 'label' => t('Screen name'), 'type' => 'input'),
                                array ('name' => 'uri', 'label' => t('URI'), 'type' => 'input'),
			),
			'update' => array(
                                array ('name' => 'name', 'label' => t('Screen name'), 'type' => 'input', 'rules' => array('required')),
                                array ('name' => 'uri', 'label' => t('URI'), 'type' => 'input', 'rules' => array('required')),
			),
			'delete' => array(
                                array ('name' => 'name', 'label' => t('Screen name'), 'type' => 'input', 'rules' => array('required')),
                                array ('name' => 'uri', 'label' => t('URI'), 'type' => 'input', 'rules' => array('required')),
			),
			'datasource' => array(
				'table' => 'sc_screens',
			),
			'properties' => array(
				'name' => 'screens',
				'uri' => 'screens/index',
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
	 * Index Page for this controller.
	 * 
	 */
	public function index($param=NULL) {
		if ($this->auth->get_access()) {
			$data['menu']['box'] = $this->template->menu_box();
			$data['login'] = $this->template->get_login();
			$data['crud'] = $this->_get_crud_for_index();
			$this->load->view('screens_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}
	
}

/* End of file screens.php */
/* Location: ./application/controllers/screens.php */
