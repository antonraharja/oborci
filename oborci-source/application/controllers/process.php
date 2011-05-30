<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Process controller
 *
 * @property oci_auth $oci_auth
 * @property oci_themes $oci_themes
 *
 * @author Anton Raharja
 *
 */
class Process extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('oborci/oci_auth', 'oborci/oci_themes'));
                $this->load->library(array('uri'));
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		if ($this->session->userdata('login_state')) {
			redirect('welcome');
		} else {
			$data['menu']['box'] = $this->oci_themes->menu_box();
			$data['login']['form'] = $this->oci_themes->login_form();
			$this->load->view('process/unauthorized', $data);
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
                        if ($this->oci_auth->authenticate($username, $password)) {
                                if ($this->oci_auth->login()) {
                                        redirect('welcome');
                                }
                        }
                        $data['login']['message'] = t('Invalid login');
                }
		$data['menu']['box'] = $this->oci_themes->menu_box();
		$data['login']['form'] = $this->oci_themes->login_form();
		$this->load->view('process/login_view', $data);
	}

	/**
	 * Process logout
	 */
	public function logout() {
		$this->oci_auth->logout();
		$data['menu']['box'] = $this->oci_themes->menu_box();
		$this->load->view('process/logout_view', $data);
	}

	/**
	 * Process unauthorized
	 */
	public function unauthorized() {
		$data['menu']['box'] = $this->oci_themes->menu_box();
		$data['login']['form'] = $this->oci_themes->login_form();
		$this->load->view('process/unauthorized', $data);
	}

}

/* End of file process.php */
/* Location: ./application/controllers/process.php */
