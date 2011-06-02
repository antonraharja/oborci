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
        
        public function get_vars() {
                // ['windowTitle', 'panelTitleWest', 'panelTitleCenter', 
                // 'panelHtmlCenter', 'labelTextLogin', 'buttonTextHome', 
                // 'buttonTextMain', 'buttonTextLogout', 'panelItemsOptions', 'menuItemsMain']
                $returns = array(
                    'returns' => array(
                        array(
                            'windowTitle' => '<div style="font-size : 2em; height: 40px; padding-top: 10px;">OborCI Example2</div><div style="padding-bottom: 10px;"><a href="http://github.com/antonraharja/oborci">Get source code</a></div>',
                            'panelTitleWest' => 'Navigations',
                            'panelTitleCenter' => 'Dashboard',
                            'panelHtmlCenter' => 'Welcome to OborCI project Example2',
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

}

/* End of file app_model.php */
/* Location: ./application/models/example2/app_model.php */
