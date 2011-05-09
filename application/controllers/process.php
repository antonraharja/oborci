<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Process controller
 * 
 * @property SC_auth $SC_auth
 * @property SC_template $SC_template
 * 
 * @author Anton Raharja
 *
 */
class Process extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('SC_auth', 'SC_template'));
		$this->load->helper(array('form'));
		$this->load->library(array('uri'));
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		if ($this->session->userdata('login_state')) {
			redirect('home');
		} else {
			$data['menu']['box'] = $this->SC_template->menu_box();
			$data['login']['form'] = $this->SC_template->login_form();
			$this->load->view('process/unauthorized', $data);
		}
	}

	/**
	 * Process login, insert parameter with a string 'ajax' for JSON result
	 * @param string $ajax
	 */
	public function login($ajax=NULL) {
		$ok = FALSE;
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		if ($this->SC_auth->auth($username, $password)) {
			if ($this->SC_auth->login()) {
				$ok = TRUE;
			}
		}
		if ($ok) {
			if (strtolower($ajax) == 'ajax') {
				$data = array('state' => TRUE, 'message' => _('Welcome') . ' ' . $username);
				echo json_encode($data);
			} else {
				redirect('home');
			}
		} else {
			if (strtolower($ajax) == 'ajax') {
				$data = array('state' => FALSE, 'message' => _('Invalid login, please try again'));
				echo json_encode($data);
			} else {
				$data['menu']['box'] = $this->SC_template->menu_box();
				$data['login']['form'] = $this->SC_template->login_form();
				$this->load->view('process/login_view', $data);
			}
		}
	}

	/**
	 * Process logout
	 */
	public function logout() {
		$this->SC_auth->logout();
		$data['menu']['box'] = $this->SC_template->menu_box();
		$this->load->view('process/logout_view', $data);
	}
	
	/**
	 * Process unauthorized
	 */
	public function unauthorized() {
		$data['menu']['box'] = $this->SC_template->menu_box();
		$data['login']['form'] = $this->SC_template->login_form();
		$this->load->view('process/unauthorized', $data);		
	}

}

/* End of file process.php */
/* Location: ./application/controllers/process.php */
