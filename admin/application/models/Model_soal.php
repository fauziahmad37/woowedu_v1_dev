<?php

class Model_soal extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAll(array $filters = NULL, int $limit= NULL, int $offset = NULL) {
        $query = $this->queryString();

		$dataArr = [];
        if(!empty($filters))
        {

        }
 
        if(!empty($filters[3]['search']['value']))
        {
					 
            $dataArr[] = $filters[3]['search']['value'];
            $query .= " AND c.class_level_id = ?";
        } 
				

        if(!empty($filters[5]['search']['value']))
        {
					 
            $dataArr[] = $filters[5]['search']['value'];
            $query .= " AND a.type = ?";
        } 

        if(!empty($filters[6]['search']['value']))
        {
					 
            $dataArr[] = $filters[6]['search']['value'];
            $query .= " AND b.sekolah_id = ?";
        } 
				

        if(!empty($filters[7]['search']['value']))
        {
					 
            $dataArr[] = $filters[7]['search']['value'];
            $query .= " AND a.subject_id = ?";
        } 
				
				
        if(!empty($limit) && !is_null($offset))
        {
            $query .= " LIMIT {$limit} OFFSET {$offset}";
        } 
        $res = $this->db->query($query,$dataArr);
        return $res->result_array();
    }

    public function countAll(array $filters = NULL) {
        $query = $this->queryString();
		$dataArr = [];

        if(!empty($filters[6]['search']['value']))
        {			 
			$dataArr[] = $filters[6]['search']['value'];
			$query .= " AND b.sekolah_id = ?";
		}

       $res = $this->db->query($query,$dataArr);
        return $res->num_rows();
    }

    public function getByCode($code) {
        $query = $this->queryString();
        $query .= " AND a.code=?";
        $res = $this->db->query($query, [$code]);
        return $res->row_array();
    }

     private function queryString() {
         $query = "SELECT a.*, b.subject_name as nama_mapel, c.class_level_name as nama_kelas , c.class_level_id as id_kelas, b.sekolah_id
										FROM soal a JOIN subject b ON a.subject_id=b.subject_id JOIN class_level c ON b.class_level_id=c.class_level_id 
											where c.class_level_name IS NOT NULL  ";
         return $query;
     }
}
