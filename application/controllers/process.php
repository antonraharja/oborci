<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Process controller
 *
 * @property auth $auth
 * @property template $template
 *
 * @author Anton Raharja
 *
 */
class Process extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->library(array('uri', 'speedcoding/Auth', 'speedcoding/Template'));
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		if ($this->session->userdata('login_state')) {
			redirect('home');
		} else {
			$data['menu']['box'] = $this->template->menu_box();
			$data['login']['form'] = $this->template->login_form();
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
		if ($this->auth->authenticate($username, $password)) {
			if ($this->auth->login()) {
				$ok = TRUE;
			}
		}
		if ($ok) {
			if (strtolower($ajax) == 'ajax') {
				$data = array('state' => TRUE, 'message' => t('Welcome') . ' ' . $username);
				echo json_encode($data);
			} else {
				redirect('home');
			}
		} else {
			if (strtolower($ajax) == 'ajax') {
				$data = array('state' => FALSE, 'message' => t('Invalid login, please try again'));
				echo json_encode($data);
			} else {
				$data['menu']['box'] = $this->template->menu_box();
				$data['login']['form'] = $this->template->login_form();
				$this->load->view('process/login_view', $data);
			}
		}
	}

	/**
	 * Process logout
	 */
	public function logout() {
		$this->auth->logout();
		$data['menu']['box'] = $this->template->menu_box();
		$this->load->view('process/logout_view', $data);
	}

	/**
	 * Process unauthorized
	 */
	public function unauthorized() {
		$data['menu']['box'] = $this->template->menu_box();
		$data['login']['form'] = $this->template->login_form();
		$this->load->view('process/unauthorized', $data);
	}

}

/* End of file process.php */
/* Location: ./application/controllers/process.php */
