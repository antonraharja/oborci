<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Template management model
 *
 * @property SC_acl $SC_acl
 * @property SC_auth $SC_auth
 * @property SC_users $SC_users
 * @property SC_menus $SC_menus
 * @property SC_roles $SC_roles
 *
 * @author Anton Raharja
 */
class SC_template extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->model(array('SC_ACL', 'SC_auth', 'SC_users', 'SC_roles', 'SC_menus'));
		$this->load->library(array('table'));
		log_message('debug', 'SC_template constructed');
	}

	/**
	 * Create login form
	 * @param string $id_css_prefix
	 * @return NULL
	 */
	public function login_form($id_css_prefix='login') {
		$data = form_open('process/login', 'id="'.$id_css_prefix.'_form"');
		$this->table->add_row(_('Username'), ':', form_input('username', '', 'id="'.$id_css_prefix.'_username"'));
		$this->table->add_row(_('Password'), ':', form_password('password', '', 'id="'.$id_css_prefix.'_password"'));
		$this->table->add_row(form_submit('submit', _('Submit'), 'id="'.$id_css_prefix.'_submit"'));
		$data .= $this->table->generate();
		$data .= form_close();
		return $data;
	}

	/**
	 * Create menu box
	 * @param string $box_id_css
	 * @return NULL, string
	 */
	public function menu_box($box_id_css=NULL) {
		$data = NULL;
		if (!isset($box_id_css)) {
			$box_id_css = "menu_item";
		}
		$menus = $this->menu_array();
		foreach ($menus as $menu) {
			$data .= '<div id="' . $box_id_css . '">' . anchor($menu['uri'], _($menu['text']), 'title="' . _($menu['title']) . '" id="' . $menu['id_css'] . '"') . '</div>';
		}
		return $data;
	}

	/**
	 * Get menu array containing menu items
	 * @return array
	 */
	public function menu_array() {
		$data = array();
		if ($this->SC_ACL->get_access()) {
			$role_id = $this->SC_users->get_role_id($this->SC_auth->get_user_id());
			$returns = $this->SC_roles->get_menu_id($role_id);
			foreach ($returns as $row) {
				$menu = $this->SC_menus->get($row->menu_id);
				if (count($menu) > 0) {
					$data[] = array(
                                            'parent' => $menu[0]->parent,
                                            'index' => $menu[0]->index,
                                            'uri' => $menu[0]->uri,
                                            'text' => _($menu[0]->text),
                                            'title' => _($menu[0]->title),
                                            'id_css' => $menu[0]->id_css
					);
				}
			}
		} else {
			$data[] = array(
                            'parent' => 0,
                            'index' => 0,
                            'uri' => 'home',
                            'text' => _('Home'),
                            'title' => _('Home'),
                            'id_css' => 'menu_home'
                            );
		}
		return $data;
	}

}

/* End of file sc_template.php */
/* Location: ./application/models/sc_template.php */
