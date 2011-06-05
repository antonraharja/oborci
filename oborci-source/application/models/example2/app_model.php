<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Welcome page model
 * 
 * @property auth $auth
 *
 * @author Anton Raharja
 */
class App_Model extends CI_Model {
        
	function __construct() {
                parent::__construct();
                $this->load->library('oborci/Auth');
	}
        
        /**
         * Get variables for dashboard
         * @return array Dahsboard variables
         */
        public function get_vars() {
                // ['windowTitle', 'panelTitleWest', 'panelTitleCenter', 
                // 'panelHtmlCenter', 'labelTextLogin', 'buttonTextHome', 
                // 'buttonTextMain', 'buttonTextLogout', 'panelItemsOptions', 'menuItemsMain']
                $returns = array(
                    'returns' => array(
                        array(
                            'loginState' => $this->auth->get_login_state(),
                            'windowTitle' => '<div style="font-size : 2em; height: 40px; padding-top: 10px;">OborCI Example2</div><div style="padding-bottom: 10px;"><a href="http://github.com/antonraharja/oborci">Get source code</a></div>',
                            'panelTitleWest' => 'Navigations',
                            'panelTitleCenter' => 'Dashboard',
                            'panelHtmlCenter' => '<div style="font-size : 2em; height: 40px; padding: 5px 5px 5px 5px;">Welcome to OborCI Example2</div>',
                            'labelTextLogin' => 'Welcome: '.$this->auth->username,
                            'buttonTextHome' => 'Home',
                            'buttonTextMain' => 'Main',
                            'buttonTextLogout' => 'Logout',
                            'panelItemsOptions' => array(
                                array('id' => 'panelOptions1', 'title' => 'Option 1', 'html' => 'This is option 1'),
                                array('id' => 'panelOptions2', 'title' => 'Option 2', 'html' => 'This is option 2'),
                                array('id' => 'panelOptions3', 'title' => 'Option 3', 'html' => 'This is option 3'),
                            ),
                            'menuItemsMain' => array(
                                array('id' => 'menutMain1', 'text' => 'Role Management'),
                                array('id' => 'menutMain2', 'text' => 'User Management'),
                                array('id' => 'menutMain3', 'text' => 'Menu Management'),
                                array('id' => 'menutMain4', 'text' => 'Screen Management'),
                            ),
                        )
                    ));
                return $returns;
        }
        
        /**
         * Get unauthorized response
         * @return array Unauthorized response
         */
        public function get_unauthorized() {
                $returns = array(
                    'returns' => array(
                        array(
                            'loginState' => FALSE,
                        )
                     )
                 );
                return $returns;
        }
        
}

/* End of file app_model.php */
/* Location: ./application/models/example2/app_model.php */
