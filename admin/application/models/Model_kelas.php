<?php
class Model_settings extends CI_Model{
	
	private $settings = "settings";

	public function get_settings()
	{
		$this->db->select('a.*');
		$this->db->from('settings a');
		$this->db->where('id', 1);
		$query=$this->db->get();
		$data = $query->row();
		return $data;
	}
 
		
	
}