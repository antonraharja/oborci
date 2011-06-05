<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Process controller
 *
 * @property auth $auth
 * @property themes $themes
 *
 * @author Anton Raharja
 *
 */
class Process extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('example1/themes'));
                $this->load->library(array('oborci/Auth', 'uri'));
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		if ($this->session->userdata('login_state')) {
			redirect('example1/welcome');
		} else {
			$data['menu']['box'] = $this->themes->menu_box();
			$data['login']['form'] = $this->themes->login_form();
			$this->load->view('example1/process/unauthorized', $data);
		}
	}

	/**
	 * Process login
	 */
	public function login() {
		$ok = FALSE;
		$username = $this->input->post('username');
		$password = $this->input->post('password');
                if (! (empty($username) && empty($password))) {
                        if ($this->auth->authenticate($username, $password)) {
                                if ($this->auth->login()) {
                                        redirect('example1/welcome');
                                }
                        }
                        $data['login']['message'] = t('Invalid login');
                }
		$data['menu']['box'] = $this->themes->menu_box();
		$data['login']['form'] = $this->themes->login_form();
		$this->load->view('example1/process/login_view', $data);
	}

	/**
	 * Process logout
	 */
	public function logout() {
		$this->auth->logout();
		$data['menu']['box'] = $this->themes->menu_box();
		$this->load->view('example1/process/logout_view', $data);
	}

	/**
	 * Process unauthorized
	 */
	public function unauthorized() {
		$data['menu']['box'] = $this->themes->menu_box();
		$data['login']['form'] = $this->themes->login_form();
		$this->load->view('example1/process/unauthorized', $data);
	}

}

/* End of file process.php */
/* Location: ./application/controllers/process.php */
