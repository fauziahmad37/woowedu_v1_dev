<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kepsek_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		// Load database library
		$this->load->database();
	}

	// Example function to get all records
	public function get_all() {
		$query = $this->db->get('kepsek');
		return $query->result();
	}

	// Example function to get a record by ID
	public function get_by_id($id) {
		$query = $this->db->get_where('kepsek', array('id' => $id));
		return $query->row();
	}

	// Example function to insert a new record
	public function insert($data) {
		return $this->db->insert('kepsek', $data);
	}

	// Example function to update a record
	public function update($id, $data) {
		$this->db->where('id', $id);
		return $this->db->update('kepsek', $data);
	}

	// Example function to delete a record
	public function delete($id) {
		$this->db->where('id', $id);
		return $this->db->delete('kepsek');
	}
}
?>
