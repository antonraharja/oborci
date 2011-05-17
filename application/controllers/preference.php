<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Roles controller
 *
 * @property SC_users $SC_users
 * @property SC_preferences $SC_preferences
 * @property auth $auth
 * @property crud $crud
 * @property template $template
 *
 * @author Anton Raharja
 *
 */
class Preference extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('speedcoding/SC_preferences', 'speedcoding/SC_users'));
                $this->load->library(array('speedcoding/Auth', 'speedcoding/Crud', 'speedcoding/Template'));
		$this->auth->validate();
	}

	/**
	 * Index Page for this controller.
	 * 
	 */
	public function index($param=NULL) {
		if ($this->auth->get_access()) {
			$data['menu']['box'] = $this->template->menu_box();
			$data['login'] = $this->template->get_login();
			// $data['crud'] = $this->_get_crud_for_index();
			$this->load->view('roles_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}
	
	public function show($param=NULL) {
		if ($this->auth->get_access()) {
			$data['menu']['box'] = $this->template->menu_box();
			$data['login'] = $this->template->get_login();

			$ok = FALSE;
			$user_id = $param;
			if (isset($user_id)) {
                                $query = $this->SC_users->get($user_id);
                                $data_user = $query->row();
                                $preference_id = (integer) $data_user->preference_id;
				if ($preference_id > 0) {
					$ok = TRUE;
				} else {
                                        $data_pref = array('first_name' => $data_user->username);
                                        $new_preference_id = $this->SC_preferences->insert($data_pref);
                                        if ($new_preference_id > 0) {
                                                $data_pref = array('preference_id' => $new_preference_id);
                                                if ($this->SC_users->update($user_id, $data_pref)) {
                                                        $data['crud'] = t('New preferences has been created');
                                                        $preference_id = $new_preference_id;
                                                        $ok = TRUE;
                                                } else {
                                                        $data['crud'] = t('Fail to update user data');
                                                }
                                        } else {
                                                $data['crud'] = t('Fail to create new user preferences');
                                        }
				}
			}
			
			if ($ok) {
				$query = $this->SC_preferences->get($preference_id);
                                $row = $query->result_array();
				$data['crud'] = print_r($row, TRUE);
			} else {
				$data['crud'] = t('No such user or preferences data');
			}
			
			$this->load->view('preference_show_view', $data);
		} else {
			redirect('process/unauthorized');
		}
	}

}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */
