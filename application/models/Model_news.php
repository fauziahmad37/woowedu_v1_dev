<?php

class Model_news extends CI_Model {

	public function __construct(){
        parent::__construct();
    }
	
	public function get_news(){
		$this->db->where('sekolah_id', $_SESSION['sekolah_id']);
		$this->db->limit('3');
		$this->db->order_by('tanggal', 'desc');
		return $this->db->get('news')->result_array();
	}

	public function get_history($limit = null, $page = null, $title, $startDate, $endDate){
		if(!empty($title))
			$this->db->where('LOWER(judul) LIKE \'%'.trim(strtolower($title)).'%\'', NULL, FALSE);

		if(!empty($startDate))
			$this->db->where('date(tanggal) >=', date('Y-m-d', strtotime($startDate)));
		
		if(!empty($endDate))
			$this->db->where('date(tanggal) <=', date('Y-m-d', strtotime($endDate)));

		$this->db->where('sekolah_id', $_SESSION['sekolah_id']);
		$this->db->select('n.*');
		$this->db->from('news n');
		$this->db->order_by('tanggal', 'DESC');
		$this->db->limit($limit, $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_total_history($title, $startDate, $endDate){
		if(!empty($title))
			$this->db->where('LOWER(judul) LIKE \'%'.trim(strtolower($title)).'%\'', NULL, FALSE);

		if(!empty($startDate))
			$this->db->where('date(tanggal) >=', date('Y-m-d', strtotime($startDate)));
		
		if(!empty($endDate))
			$this->db->where('date(tanggal) <=', date('Y-m-d', strtotime($endDate)));

		$this->db->where('sekolah_id', $_SESSION['sekolah_id']);
		$query = $this->db->get('news');
		return $query->num_rows();
	}

}
