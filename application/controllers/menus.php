<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Menus controller
 *
 * @property auth $auth
 * @property crud $crud
 * @property template $template
 *
 * @author Anton Raharja
 *
 */
class Menus extends CI_Controller {

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
                                array ('name' => 'parent', 'label' => t('Parent'), 'type' => 'input'),
                                array ('name' => 'index', 'label' => t('Index'), 'type' => 'input'),
                                array ('name' => 'uri', 'label' => t('URI'), 'type' => 'input', 'rules' => array('required')),
                                array ('name' => 'text', 'label' => t('Menu text'), 'type' => 'input', 'rules' => array('required')),
                                array ('name' => 'title', 'label' => t('Menu title'), 'type' => 'input', 'rules' => array('required')),
			),
			'select' => array(
				array('name' => 'id', 'label' => 'ID', 'rules' => array('key', 'hidden'),),
                                array ('name' => 'parent', 'label' => t('Parent'), 'type' => 'input'),
                                array ('name' => 'index', 'label' => t('Index'), 'type' => 'input'),
                                array ('name' => 'uri', 'label' => t('URI'), 'type' => 'input'),
                                array ('name' => 'text', 'label' => t('Menu text'), 'type' => 'input'),
                                array ('name' => 'title', 'label' => t('Menu title'), 'type' => 'input'),
			),
			'update' => array(
                                array ('name' => 'parent', 'label' => t('Parent'), 'type' => 'input'),
                                array ('name' => 'index', 'label' => t('Index'), 'type' => 'input'),
                                array ('name' => 'uri', 'label' => t('URI'), 'type' => 'input', 'rules' => array('required')),
                                array ('name' => 'text', 'label' => t('Menu text'), 'type' => 'input', 'rules' => array('required')),
                                array ('name' => 'title', 'label' => t('Menu title'), 'type' => 'input', 'rules' => array('required')),
			),
			'delete' => array(
                                array ('name' => 'parent', 'label' => t('Parent'), 'type' => 'input'),
                                array ('name' => 'index', 'label' => t('Index'), 'type' => 'input'),
                                array ('name' => 'uri', 'label' => t('URI'), 'type' => 'input'),
                                array ('name' => 'text', 'label' => t('Menu text'), 'type' => 'input'),
                                array ('name' => 'title', 'label' => t('Menu title'), 'type' => 'input'),
			),
			'datasource' => array(
				'table' => 'sc_menus',
			),
			'properties' => array(
				'name' => 'menus',
				'uri' => 'menus/index',
				'index_column' => TRUE,
				'index_column_start' => 1,
				'pagination_per_page' => 10,
				'insert' => TRUE,
				'update' => TRUE,
				'delete' => TRUE,
				'crud_form_title' => '<h2>'.t('List of Menus').'</h2>',
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
			$this->load->view('menus_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}
	
}

/* End of file menus.php */
/* Location: ./application/controllers/menus.php */
