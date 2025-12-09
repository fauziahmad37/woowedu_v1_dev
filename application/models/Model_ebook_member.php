<?php

class Model_ebook_member extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

	public function getAll($limit, $offset, $filter=[]){
		$this->db->select('e.*, p.publisher_name, em.read_status, em.start_activation, em.end_activation, em.ebook_id');
		$this->db->from('ebook_members em');
		$this->db->join('ebooks e', 'e.id=em.ebook_id');
		$this->db->join('publishers p', 'p.id=e.publisher_id', 'left');

		if(isset($filter['user_id']) && !empty($filter['user_id']))
			$this->db->where('em.user_id', $filter['user_id']);

		return $this->db->get()->result_array();
	}

	public function getCountAll($filter=[]){
		$this->db->select('*');
		$this->db->from('ebook_members em');
		$this->db->join('ebooks e', 'e.id=em.ebook_id');
		$this->db->join('publishers p', 'p.id=e.publisher_id', 'left');

		if(isset($filter['user_id']) && !empty($filter['user_id']))
			$this->db->where('em.user_id', $filter['user_id']);

		return $this->db->get()->num_rows();
	}

}
