<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form library
 *
 * @author Anton Raharja
 */
class Form {

        public $uri = NULL;
        public $name = NULL;
        
        private $data = NULL;
        private $returns = NULL;
        
        private $rules = NULL;
	private $form_name = 'Form';
	private $CI = NULL;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->helper('form');
	}

	/**
	 * Create form open
	 * @param array $data Data array
         * @return string HTML form open
	 */
	public function open($data=NULL) {
                $data = $this->_setup_rules($data);
                $uri = isset($this->uri) ? $this->uri : $data['uri'];
                if (empty($uri)) {
                        $uri = current_url();
                }
                $this->data[]['uri'] = $uri;
                $name = isset($this->name) ? $this->name : $data['name'];
                if (empty($name)) {
                        $name = 'form'.mktime();
                }
                $data['name'] = $name;
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
                $this->data[] = array('open' => $data);
		$returns = form_open($uri, $data);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create form close
	 * @param array $data Data array
         * @return string HTML form close
	 */
	public function close($data=NULL) {
                $data = $this->_setup_rules($data);
                $this->data[] = array('close' => $data);
		$returns = form_close($data['value']);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create checkbox
	 * @param array $data Data array
         * @return string HTML form checkbox
	 */
	public function checkbox($data=NULL) {
                $data = $this->_setup_rules($data);
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['checked']) {
			$data['checked'] =  'checked';
		}
                $this->data[] = array('checkbox' => $data);
		$returns = form_checkbox($data);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create radio button
	 * @param array $data Data array
         * @return string HTML form radio
	 */
	public function radio($data=NULL) {
                $data = $this->_setup_rules($data);
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['checked']) {
			$data['checked'] =  'checked';
		}
                $this->data[] = array('radio' => $data);
		$returns = form_radio($data);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create dropdown
	 * @param array $data Data array
         * @return string HTML form dropdown
	 */
	public function dropdown($data=NULL) {
                $data = $this->_setup_rules($data);
		$extra = NULL;
		$returns = '<div id="form_dropdown">';
		$name = $data['name'];
		$options = $data['options'];
		$selected = isset($data['selected']) ? $data['selected'] : '';
		$data['extra']['id'] = isset($data['id']) ? $data['id'] : $data['extra']['id'];
		$data['extra']['id'] = isset($data['extra']['id']) ? $data['extra']['id'] : $data['name'];
		foreach ($data['extra'] as $key => $val) {
			$extra .= $key.'='.$val.' ';			
		}		
		if ($data['label']) {
			$attr = array('id' => $data['extra']['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
		$returns .= form_dropdown($name, $options, $selected, $extra);
		$returns .= "</div>";
                $this->data[] = array('dropdown' => $data);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create hidden input
	 * @param array $data Data array
         * @return HTML form hidden
	 */
	public function hidden($data=NULL) {
                $data = $this->_setup_rules($data);
		$name = $data['name'];
		$value = $data['value'];
                $this->data[] = array('hidden' => $data);
		$returns = form_hidden($name, $value);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create label
	 * @param array $data Data array
         * @return string HTML form label
	 */
	public function label($data=NULL) {
                $data = $this->_setup_rules($data);
		$returns = NULL;
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['label']) {
			$attr = array('id' => $data['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
                $this->data[] = array('label' => $data);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create text input
	 * @param array $data Data array
         * @return string HTML form input
	 */
	public function input($data=NULL) {
                $data = $this->_setup_rules($data);
		$returns = '<div id="form_input">';
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['label']) {
			$attr = array('id' => $data['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
		if ($data['readonly']) {
			$data['readonly'] = 'readonly';
		}
		if ($data['disabled']) {
			$data['disabled'] = 'disabled';
		}
		$returns .= form_input($data);
		$returns .= "</div>";
                $this->data[] = array('input' => $data);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create password input
	 * @param array $data Data array
         * @return string HTML form password
	 */
	public function password($data=NULL) {
                $data = $this->_setup_rules($data);
		$returns = '<div id="form_password">';
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
		if ($data['label']) {
			$attr = array('id' => $data['id'].'_label');
			$returns .= form_label($data['label'], $data['name'], $attr);
		}
		$returns .= form_password($data);
		$returns .= "</div>";
                $this->data[] = array('password' => $data);
                $this->returns[] = $returns;
                return $returns;
	}
	
	/**
	 * Create submit button
	 * @param array $data Data array
         * @return string HTML form submit
	 */
	public function submit($data=NULL) {
                $data = $this->_setup_rules($data);
		if (! isset($data['name'])) {
			$data['name'] = 'form_submit';
		}
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
                $this->data[] = array('submit' => $data);
		$returns = form_submit($data);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create reset button
	 * @param array $data Data array
         * @return string HTML form reset
	 */
	public function reset($data=NULL) {
                $data = $this->_setup_rules($data);
		if (! isset($data['name'])) {
			$data['name'] = 'form_reset';
		}
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
                $this->data[] = array('reset' => $data);
		$returns = form_reset($data);
                $this->returns[] = $returns;
                return $returns;
	}

	/**
	 * Create plain button
	 * @param array $data Data array
         * @return string HTML form button
	 */
	public function button($data=NULL) {
                $data = $this->_setup_rules($data);
		if (! isset($data['name'])) {
			$data['name'] = 'form_button';
		}
		$data['id'] = isset($data['id']) ? $data['id'] : $data['name'];
                $this->data[] = array('button' => $data);
		$returns = form_button($data);
                $this->returns[] = $returns;
                return $returns;
	}
        
        /**
         * Initialize form, nullify all parameters and start fresh
         */
        public function init() {
                $this->_nullify_params();
        }

	/**
         * Set rules to each field
         * @param array $data Rules array
         */
        public function set_rules($data) {
                $this->rules = $data;
        }
        
        /**
	 * Set uniquely formatted data structure
	 * Usage example: $this->form->set_data($data);
	 * @param array $data Data array
	 * @return NULL
	 */
	public function set_data($data) {
		$this->data = $data;
	}
        
        /**
         * Call function on event on_success
         * @param array $data Function
         */
        public function on_success($data) {
                $this->data[]['events']['on_success'] = $data;
        }

	/**
	 * Render form
	 * Usage example: return $this->form->render();
	 * @return string $returns HTML form
	 */
	public function render() {
		$returns = NULL;
		$form_open_exists = FALSE;
		$form_close_exists = FALSE;
                $data = $this->data;
                $rules = $this->rules;
                $this->_nullify_params();
		foreach ($data as $field_key => $field_val) {
                        foreach ($field_val as $method => $param) {
                                if (method_exists($this->form_name, $method)) {
                                        if (! isset($param['rules'])) {
                                                if (isset($rules[$param['name']])) {
                                                        $param['rules'] = $rules[$param['name']];
                                                }
                                        }
                                        call_user_func_array(array($this->form_name, $method), array($param));
                                }
                                if ($method == 'open') {
                                        $form_open_exists = TRUE;
                                }
                                if ($method == 'close') {
                                        $form_close_exists = TRUE;
                                }
                        }
		}
		if ($form_open_exists && !$form_close_exists) {
			$this->close();
		}
                $returns = implode($this->returns);
                $this->_nullify_params();
                return $returns;
	}

        /**
         * Helper function to nullify parameters
         */
        private function _nullify_params() {
                $this->data = NULL;
                $this->returns = NULL;
                $this->rules = NULL;
                $this->uri = NULL;
                $this->name = NULL;
        }
        
        /**
         * Helper function to setup rules in data array
         */
        private function _setup_rules($data) {
                if (isset($data['rules'])) {
                        $rules = $data['rules'];
                        foreach ($rules as $rule_key => $rule_val) {
                                if (is_array($rule_val)) {
                                        foreach ($rule_val as $sub_rule_key => $sub_rule_val) {
                                                $data[$sub_rule_key] = $sub_rule_val;
                                        }
                                } else {
                                        $data[$rule_val] = TRUE;
                                }
                        }
                }
                unset($data['rules']);
                return $data;
        }
}

/* End of file Form.php */
