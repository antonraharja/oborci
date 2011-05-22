<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Roles controller
 *
 * @property SC_users $SC_users
 * @property SC_preferences $SC_preferences
 * @property auth $auth
 * @property form $form
 * @property template $template
 *
 * @author Anton Raharja
 *
 */
class Preference extends CI_Controller {

	function __construct() {
		parent::__construct();
                $this->load->model(array('oborci/SC_preferences', 'oborci/SC_users'));
                $this->load->library(array('oborci/Auth', 'oborci/Form', 'oborci/Template'));
                if (! $this->auth->validate()) {
                        redirect('process/unauthorized');
                }
	}

        private function _show_form($row) {
                $this->form->init();
                $this->form->set_name('show');
                $this->form->set_rules(
                        array(
                        'id' => array('readonly'),
                        'email' => array('required', array('max_length' => 200), 'trim'),
                        'first_name' => array('required', array('max_length' => 50), 'trim'),
                        'last_name' => array(array('max_length' => 50), 'trim')));
                $this->form->set_on_success(array(__CLASS__, 'save_form'));
                $this->form->open();
                foreach ($row as $key => $val) {
                        if ($key=='id') {
                                $this->form->hidden(array('name' => 'key_id', 'value' => $val));
                        }
                        $label = str_replace('_', ' ', $key);
                        $this->form->input(array('name' => $key, 'label' => ucwords(t($label)), 'value' => $val));
                }
                $this->form->submit(array('value' => t('Submit')));
                $this->form->close();
                $returns = $this->form->render();
                return $returns;
        }
        
        public  function _save_form($inputs) {
                $id = $inputs['key_id'];
                unset($inputs['key_id']);
                $CI =& get_instance();
                $CI->SC_preferences->update($id, $inputs);
                // redirect(current_url());
                $returns = '<p>'.t('Preferences has been updated').'</p>';
                $returns .= '<p>'.  anchor(current_url(), t('Back'), 'title="'.t('Back').'"').'</p>';
                return $returns;
        }
        
	/**
	 * Index Page for this controller.
	 */
	public function index() {
                redirect('home');
        }
	
	/**
         * Show preferences
         * @param integer $param User ID
         */
        public function show($param=NULL) {
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
                        $row = $query->row_array();
                        $data['crud'] = $this->_show_form($row);
                        $data['pref']['username'] = $data_user->username;
                } else {
                        $data['crud'] = t('No such user or preferences data');
                }

                $this->load->view('preference_show_view', $data);
	}

}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */
