<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Template library
 *
 * @property SC_menus $SC_menus
 * @property SC_preferences $SC_preferences
 * @property SC_roles $SC_roles
 * @property SC_users $SC_users
 * @property auth $auth
 * @property form $form
 *
 * @author Anton Raharja
 */
class Template {
        
        private $CI = NULL;

	function __construct() {
                $this->CI =& get_instance();
		$this->CI->load->model(
			array(
				'speedcoding/SC_menus', 
				'speedcoding/SC_preferences', 
				'speedcoding/SC_roles', 
				'speedcoding/SC_users'
			)
		);
		$this->CI->load->library(array('table', 'speedcoding/Auth', 'speedcoding/Form'));
	}

	/**
	 * Get login information
	 * @return array|boolean Array of logged in user data or FALSE when user not authenticated
	 */
	public function get_login() {
                $data = NULL;
		$query = $this->CI->SC_preferences->get($this->CI->auth->preference_id);
                $pref = $query->row_array();
		if (isset($pref['id'])) {
                        $data = $pref;
		}
		$query = $this->CI->SC_roles->get($this->CI->auth->role_id);
                $role = $query->row();
		if (isset($role->id)) {
			$data['role'] = $role->name;
		}
		return $data;
	}

	/**
	 * Create login form
	 * @param string $id_css_prefix CSS ID parameter
	 * @return NULL
	 */
	public function login_form($id_css_prefix='login') {
		$data = array(
			0 => array(
				'open' => array(
					'uri' => 'process/login',
					'name' => $id_css_prefix.'_form',
				),
			),
			1 => array(
				'input' => array(
					'name' => 'username',
					'id' => $id_css_prefix.'_username',
					'label' => 'Username',
				),
			),
			2 => array(
				'password' => array(
					'name' => 'password',
					'id' => $id_css_prefix.'_password',
					'label' => 'Password',
				),
			),
			3 => array(
				'submit' => array(
					'name' => 'submit',
					'id' => $id_css_prefix.'_submit',
					'value' => t('Submit'),
				),
			),
		);
		$this->CI->form->set_data($data);
		$data = $this->CI->form->render();
		return $data;
	}

	/**
	 * Get menu array containing menu items
	 * @return array Array of menu
	 */
	public function menu_array() {
		$data = array();
		if ($this->CI->auth->get_access()) {
			$returns = $this->CI->SC_roles->get_menu_id($this->CI->auth->role_id);
			foreach ($returns as $row) {
				$query = $this->CI->SC_menus->get($row->menu_id);
                                $menu = $query->row();
				if (isset($menu->id)) {
					$data[] = array(
						'parent' => $menu->parent,
						'index' => $menu->index,
						'uri' => $menu->uri,
						'text' => t($menu->text),
						'title' => t($menu->title),
						'id_css' => $menu->id_css
					);
				}
			}
		} else {
			$data[] = array(
				'parent' => 0,
				'index' => 0,
				'uri' => 'home',
				'text' => t('Home'),
				'title' => t('Home'),
				'id_css' => 'menu_home'
			);
		}
                sort($data);
		return $data;
	}

	/**
	 * Create menu box
	 * @param string $box_id_css CSS ID for menu div
	 * @return NULL, string NULL for no menu or menu box HTML
	 */
	public function menu_box($box_id_css=NULL) {
		$data = NULL;
		if (!isset($box_id_css)) {
			$box_id_css = "menu_item";
		}
		$menus = $this->menu_array();
		foreach ($menus as $menu) {
			$data .= '<div id="' . $box_id_css . '">' . anchor($menu['uri'], t($menu['text']), 'title="' . t($menu['title']) . '" id="' . $menu['id_css'] . '"') . '</div>';
		}
		return $data;
	}

}

/* End of file sc_template.php */
/* Location: ./application/models/sc_template.php */
