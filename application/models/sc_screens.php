<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * Screens management model
 *
 * @author Anton Raharja
 */
class SC_screens extends CI_Model {

	private $table_screens = 'sc_screens';

	function __construct() {
		parent::__construct();
		log_message('debug', 'SC_screens constructed');
	}

	/**
	 * Insert a new screen to database
	 * @param array $data
	 * @return integer,boolean
	 */
	public function insert($data) {
		if ($this->db->insert($this->table_screens, $data)) {
			$screen_id = $this->db->insert_id();
			if ($screen_id) {
				return $screen_id;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * Get all screens or specific screen when $screen_id given
	 * @param integer $screen_id
	 * @return array
	 */
	public function get($screen_id=NULL) {
		if (isset($screen_id)) {
			$query = $this->db->get_where($this->table_screens, array('id' => $screen_id));
		} else {
			$query = $this->db->get_where($this->table_screens);
		}
		return $query->result();
	}

	/**
	 * Update screen
	 * @param array $data
	 * @param integer $screen_id
	 * @return boolean
	 */
	public function update($data, $screen_id) {
		if (count($data) > 0) {
			$this->db->update($this->table_screens, $data, array('id' => $screen_id));
		}
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Delete screen
	 * @param integer $screen_id
	 * @return boolean
	 */
	public function delete($screen_id) {
		$this->db->delete($this->table_screens, array('id' => $screen_id));
		if ($this->db->affected_rows()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Get screen by URI data
	 * @param integer $uri
	 * @return array
	 */
	public function get_by_uri($uri) {
		$query = $this->db->get_where($this->table_screens, array('uri' => $uri));
		return $query->result();
	}

}

/* End of file sc_screens.php */
/* Location: ./application/models/sc_screens.php */