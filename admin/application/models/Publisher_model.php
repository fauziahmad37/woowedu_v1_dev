<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Publisher_model extends CI_Model {

	public function get_all(?array $filter = NULL, ?int $limit=NULL, ?int $offset=NULL): array {

        if(!empty($filter[1]['search']['value']))
            $this->db->where('LOWER(publisher_name) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);
        
        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);
            
        $this->db->where('deleted_at IS NULL');
        $query = $this->db->get('publishers');
        return $query->result_array();
    }

	public function add($data){
		$this->db->insert('publishers', $data);
		return $this->db->affected_rows();
	}

	public function update($id, $data){
		$this->db->where('id', $id);
		$this->db->update('publishers', $data);
		return $this->db->affected_rows();
	}

	public function count_all(?array $filter = NULL)
    {
        $this->db->where('deleted_at IS NULL');
        $query = $this->db->get('publishers');
        return $query->num_rows();
    }

}
