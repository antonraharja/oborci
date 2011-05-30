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
 * @property oci_auth $oci_auth
 * @property form $form
 *
 * @author Anton Raharja
 */
class oci_themes extends CI_Model {
        
	function __construct() {
		$this->load->model(
			array(
                                'oborci/oci_auth', 
				'oborci/oci_menus', 
				'oborci/oci_preferences', 
				'oborci/oci_roles', 
                                'oborci/oci_roles_menus', 
				'oborci/oci_users'
			)
		);
		$this->load->library(array('table', 'oborci/Form'));
	}

	/**
	 * Get login information
	 * @return array|boolean Array of logged in user data or FALSE when user not authenticated
	 */
	public function get_login() {
                $data = NULL;
		$query = $this->oci_preferences->get($this->oci_auth->preference_id);
                $pref = $query->row_array();
		if (isset($pref['id'])) {
                        $data = $pref;
		}
		$query = $this->oci_roles->get($this->oci_auth->role_id);
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
		if ($this->oci_auth->get_access()) {
                        $query = $this->oci_roles_menus->get_by(array('role_id' => $this->oci_auth->role_id));
			foreach ($query->result() as $row) {
				$query = $this->oci_menus->get($row->menu_id);
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
			$data .= '<div id="' . $box_id_css . '">' . anchor($menu['uri'], t($menu['text']), 'title="' . t($menu['title']) . '" id="' . $menu['id_css'] . '"') . '</div>';
		}
		return $data;
	}

}

/* End of file oci_themes.php */
/* Location: ./application/models/oci_themes.php */
