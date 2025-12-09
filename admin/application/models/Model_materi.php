<?php

class Model_materi extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(array $filter = NULL, int $limit = NULL, $offset = NULL) {
        $query = "SELECT a.subject_id, a.title, a.note, TO_CHAR(a.available_date, 'YYYY-MM-DD') as available_date, a.materi_file, 
                        c.subject_id, c.subject_name, a.materi_id,tema_title,sub_tema_title,no_urut
                  FROM materi a  
                  JOIN subject c ON a.subject_id=c.subject_id";
        
        $dataArr = [];
				
				$columns = array( 
												3 =>'subject_name',  
												4 =>'tema_title',  
												5 =>'sub_tema_title',  
												6 =>'title',								
												7 =>'no_urut' 												
												);
											
        if(!empty($filter[2]['search']['value']))
        {
            $dataArr[] = '%'.strtolower($filter[2]['search']['value']).'%';
            $query .= " AND LOWER(a.title) LIKE ?";
        } 
        if(!empty($filter[5]['search']['value']))
        {
            $dataArr[] = '%'.strtolower($filter[7]['search']['value']).'%';
            $query .= " AND LOWER(c.subject_name) LIKE ?";
        }

        if(!empty($_GET['order'][0]['column']))
					$query .= " ORDER BY ".$columns[$_GET['order'][0]['column']]." ".$_GET['order'][0]['dir'];
        else
            $query .= " ORDER BY materi_id DESC";
				
        if(!empty($limit) && !is_null($offset))
            $query .= " LIMIT {$limit} OFFSET {$offset}"; 
        $res = $this->db->query($query, $dataArr);
        return $res->result_array();
    }

    public function countAll(array $filter = NULL) {
        $query = "SELECT a.subject_id, a.title, a.note, TO_CHAR(a.available_date, 'YYYY-MM-DD') as available_date, a.materi_file, 
                          c.subject_id, c.subject_name, a.materi_id
                  FROM materi a  
                  JOIN subject c ON a.subject_id=c.subject_id";
        $res = $this->db->query($query);
        return $res->num_rows();
    }

	public function countAllGlobal(array $filter = NULL) {
        $query = "SELECT * FROM materi_global a";
        $res = $this->db->query($query);
        return $res->num_rows();
    }
		
	public function get_materi_by_subject($sid) {
		$this->db->select('materi_id, title'); 
		$this->db->from('materi');   
		$this->db->WHERE('subject_id',$sid);   
		$res = $this->db->get();
		return $res->result_array();
	}	


	public function get_materi_require_by_subject($sid) {
		$query = 	"SELECT materi_id,title 
						FROM materi
						WHERE subject_id=".$sid."
						AND materi_id NOT IN 
						(SELECT materi_require 
						FROM materi
						WHERE subject_id=".$sid." and materi_require is not null) ";
		$res = $this->db->query($query);
		return $res->result_array();
	}	
		
	/**
     * get all data
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
	public function getAllGlobal(array $filter = NULL, int $limit = NULL, $offset = NULL) {
		if(!empty($filter[0]['search']['value']))
			$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[0]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[1]['search']['value']))
			$this->db->where('parent_id', $filter[1]['search']['value']);

		if(!empty($filter[2]['search']['value']))
			$this->db->where('parent_id', null, false);

		if(!empty($limit) && !is_null($offset))
			$this->db->limit($limit, $offset);

		$this->db->order_by('a.materi_global_id ', 'DESC');
		$get = $this->db->get('materi_global a');

		return $get->result_array() ?? [];
    }

	/**
     * Num rows with filter
     *
     * @param array $filter
     * @return integer
     */
    public function num_all_global(array $filter = NULL): int {
		if(!empty($filter[0]['search']['value']))
			$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[0]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[1]['search']['value']))
			$this->db->where('parent_id', $filter[1]['search']['value']);

		if(!empty($filter[2]['search']['value']))
			$this->db->where('parent_id', null, false);

        $get = $this->db->get('materi_global a');

        return $get->num_rows() ?? 0;
    }
}
