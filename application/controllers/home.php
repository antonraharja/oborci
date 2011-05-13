<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Home controller
 *
 * @property SC_auth $SC_auth
 * @property template $template
 *
 * @author Anton Raharja
 *
 */
class Home extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model(array('speedcoding/SC_auth', 'template'));
		$this->SC_auth->validate();
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		if ($this->SC_auth->get_access()) {
			$data['menu']['box'] = $this->template->menu_box();
			$data['login'] = $this->template->get_login();
			$this->load->view('home_view', $data);
		} else {
			redirect('process/login');
		}
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
