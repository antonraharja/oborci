<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Process controller
 *
 * @property oci_auth $oci_auth
 * @property themes $themes
 *
 * @author Anton Raharja
 *
 */
class Process extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('oborci/oci_auth'));
                $this->load->library(array('uri'));
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		redirect('example2/welcome');
	}

	/**
	 * Process login
	 */
	public function login() {
		$username = $this->input->post('username');
		$password = $this->input->post('password');
                if (! (empty($username) && empty($password))) {
                        if ($this->oci_auth->authenticate($username, $password)) {
                                if ($this->oci_auth->login()) {
                                        $data['output']['success'] = TRUE;
                                        $data['output']['message'] = t('Login successful');
                                } else {
                                        $data['output']['success'] = FALSE;
                                        $data['output']['message'] = t('Login successful but an error has occured');
                                }
                        } else {
                                $data['output']['success'] = FALSE;
                                $data['output']['message'] = t('Invalid login');
                        }
                } else {
                        $data['output']['success'] = FALSE;
                        $data['output']['message'] = t('Please input username and password');
                }
                // log_message('debug', "username:$username password:$password data:".print_r($data, TRUE));
                $this->load->view('example2/json_view', $data);
	}

	/**
	 * Process logout
	 */
	public function logout() {
		$this->oci_auth->logout();
                redirect('example2/welcome');
	}

	/**
	 * Process unauthorized
	 */
	public function unauthorized() {
                $this->oci_auth->logout();
                redirect('example2/welcome');
	}

}

/* End of file process.php */
/* Location: ./application/controllers/example2/process.php */
