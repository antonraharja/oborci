<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Home controller for example2
 *
 * @property auth $auth
 * @property app_model $app_model
 *
 * @author Anton Raharja
 *
 */
class Welcome extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('example2/app_model'));
                $this->load->library(array('oborci/Auth'));
		$this->auth->validate();
	}

	/**
	 * Index Page for this controller.
         * @param string $param Is 'ajax' or NULL
	 *
	 */
	public function index($param=NULL) {
                if ($this->auth->get_access()) {
                        $this->load->view('example2/app_view');
                } else {
                        $this->load->view('example2/login_view');
                }
	}
        
        /**
         * App page
         * @param string $param Is 'ajax' or NULL
         */
        public function app($param=NULL) {
                if ($this->auth->get_access()) {
                        $data['output'] = $this->app_model->get_vars();
                        $this->load->view('example2/json_view', $data);
                }
        }
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/example2/welcome.php */
