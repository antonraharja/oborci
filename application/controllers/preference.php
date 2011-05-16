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
class Preference extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('speedcoding/SC_auth', 'template'));
		$this->load->library('speedcoding/Crud');
		$this->SC_auth->validate();
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
	
	public function view($param=NULL) {
		
	}

}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */
