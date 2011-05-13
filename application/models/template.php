<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Template model
 *
 * @property SC_auth $SC_auth
 * @property SC_menus $SC_menus
 * @property SC_preferences $SC_preferences
 * @property SC_roles $SC_roles
 * @property SC_users $SC_users
 *
 * @author Anton Raharja
 */
class Template extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->model(
			array(
				'speedcoding/SC_auth', 
				'speedcoding/SC_menus', 
				'speedcoding/SC_preferences', 
				'speedcoding/SC_roles', 
				'speedcoding/SC_users'
			)
		);
		$this->load->library(array('table', 'speedcoding/Form'));
	}

	/**
	 * Get login information
	 * @param integer $user_id User ID, if user ID omitted get_login() will get user ID from session
	 * @return array,boolean Array of logged in user data or FALSE when user not authenticated
	 */
	public function get_login($user_id=NULL) {
		if (!isset($user_id)) {
			$user_id = $this->SC_auth->get_user_id();
			if (!isset($user_id)) {
				return FALSE;
			}
		}
		$data = $this->SC_auth->get_login_id($user_id);
		$preferences = $this->SC_preferences->get($data['preference_id']);
		if (count($preferences) > 0) {
			$data['first_name'] = $preferences[0]->first_name;
			$data['last_name'] = $preferences[0]->last_name;
		}
		$role = $this->SC_roles->get($data['role_id']);
		if (count($role) > 0) {
			$data['role'] = $role[0]->name;
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
		$this->form->set_data($data);
		$data = $this->form->render();
		return $data;
	}

	/**
	 * Get menu array containing menu items
	 * @return array Array of menu
	 */
	public function menu_array() {
		$data = array();
		if ($this->SC_auth->get_access()) {
			$role_id = $this->SC_users->get_role_id($this->SC_auth->get_user_id());
			$returns = $this->SC_roles->get_menu_id($role_id);
			foreach ($returns as $row) {
				$menu = $this->SC_menus->get($row->menu_id);
				if (count($menu) > 0) {
					$data[] = array(
						'parent' => $menu[0]->parent,
						'index' => $menu[0]->index,
						'uri' => $menu[0]->uri,
						'text' => t($menu[0]->text),
						'title' => t($menu[0]->title),
						'id_css' => $menu[0]->id_css
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
