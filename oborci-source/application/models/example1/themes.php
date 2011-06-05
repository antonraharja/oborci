<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Template model
 *
 * @property oci_menus $oci_menus
 * @property oci_preferences $oci_preferences
 * @property oci_roles $oci_roles
 * @property oci_roles_menus $oci_roles_menus
 * @property oci_users $oci_users
 * @property auth $auth
 * @property form $form
 *
 * @author Anton Raharja
 */
class Themes extends CI_Model {
        
	function __construct() {
		$this->load->model(
			array(
				'oborci/oci_roles', 
				'oborci/oci_users',
                                'oborci/oci_preferences',
                                'oborci/oci_menus',
			)
		);
		$this->load->library(array('oborci/Auth', 'oborci/Form', 'table'));
	}

	/**
	 * Get login information
	 * @return array|boolean Array of logged in user data or FALSE when user not authenticated
	 */
	public function get_login() {
                $data = NULL;
		$results = $this->oci_users->find_from('oci_preferences', array('preferences' => $this->auth->preference_id));
                $pref = $results[0];
		if (isset($pref['id'])) {
                        $data = $pref;
		}
		$results = $this->oci_roles->find($this->auth->role_id);
                $role = (object) $results[0];
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
					'uri' => 'example1/process/login',
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
		if ($this->auth->get_access()) {
                        $results = $this->oci_roles->find_from('oci_menus', array('id' => $this->auth->role_id));
                        foreach ($results as $menu) {
                                $menu = (object) $menu;
                                $data[] = array(
                                        'parent' => $menu->parent,
                                        'index' => $menu->index,
                                        'uri' => $menu->uri,
                                        'text' => t($menu->text),
                                        'title' => t($menu->title),
                                        'id_css' => $menu->id_css
                                );
                        }
		} else {
			$data[] = array(
				'parent' => 0,
				'index' => 0,
				'uri' => 'welcome',
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
			$data .= '<div id="' . $box_id_css . '">' . anchor('example1/'.$menu['uri'], t($menu['text']), 'title="' . t($menu['title']) . '" id="' . $menu['id_css'] . '"') . '</div>';
		}
		return $data;
	}

}

/* End of file oci_themes.php */
/* Location: ./application/models/oci_themes.php */
