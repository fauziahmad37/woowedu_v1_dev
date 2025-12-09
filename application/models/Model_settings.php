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

	// Auditrail 
	public function get_all_log(array $filter = NULL, int $limit = NULL, int $offset = NULL) {
		$this->compiledLogQuery();
		if(!empty($filter) && is_array($filter))  {
				$i=0;
				foreach($filter as $f) {
						if(empty($f['search']['value'])) continue;
						$key= $f['data'];
						$val = $f['search']['value'];
						if($i === 0) 
								$this->db->where('LOWER('.$key.') LIKE \'%'.$this->db->escape_like_str(strtolower($val)).'%\''); 
						else
								$this->db->or_where('LOWER('.$key.') LIKE \'%'.$this->db->escape_like_str(strtolower($val)).'%\'');
						$i++;
				}
		}
		if(!empty($limit))
		$this->db->limit($limit, $offset);
		$res = $this->db->get();
		return $res->result_array();
	}	

	public function count_all_log(array $filter = NULL) {
		$this->compiledLogQuery();
		if(!empty($filter) && is_array($filter))  {
				$i=0;
				foreach($filter as $f) {
						if(empty($f['search']['value'])) continue;
						$key= $f['data'];
						$val = $f['search']['value'];
						if($i === 0) 
								$this->db->where('LOWER('.$key.') LIKE \'%'.$this->db->escape_like_str(strtolower($val)).'%\''); 
						else
								$this->db->or_where('LOWER('.$key.') LIKE \'%'.$this->db->escape_like_str(strtolower($val)).'%\'');
						$i++;
				}
		}
		return $this->db->count_all_results();
	}
	
	private function compiledLogQuery() {
		$this->db->select('*'); 
		$this->db->get_compiled_select('actionlog', FALSE);
	}   
	
}
