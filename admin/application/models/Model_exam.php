<?php

class Model_exam extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAll(array $filters = NULL, int $limit = NULL, int $offset = NULL) 
    {
        $query = $this->queryString();
        if(!empty($limit) && !is_null($offset))
            $query .= " LIMIT {$limit} OFFSET {$offset}";
        
        $res = $this->db->query($query);
        return $res->result_array();
    }

    public function countAll(array $filters = NULL)
    {
       
        $query = $this->queryString(); 
        $res = $this->db->query($query);
        return $res->num_rows();
    }

    public function getCategory() {
        $query = "SELECT * FROM exam_category";
        $res = $this->db->query($query);
        return $res->result_array();
    }

    public function getDateBetween(int $kelas = NULL, int $mapel = NULL, string $start = NULL, string $end = NULL, int $edit = NULL)
    {
        $query="SELECT * FROM exam WHERE class_id=? AND (timestamp ?, timestamp ?) OVERLAPS (start_date::timestamp, end_date::timestamp)";
        if($edit)
            $query .= " AND exam_id <> {$edit}";
        $res = $this->db->query($query, [$kelas, $start, $end]);
        return $res;
    }


    public function getAllSoalReserve($exam = NULL, $kelas = NULL, $subject = NULL) {
        $query = "SELECT *, a.code as soal_code FROM soal a 
                  JOIN kelas b ON a.class_id=b.class_id
                  JOIN subject c ON a.subject_id=c.subject_id 
                  WHERE a.class_level_id=? AND a.subject_id=? AND 
                  NOT EXISTS(SELECT d.soal_id FROM soal_exam d WHERE a.soal_id=d.soal_id AND d.exam_id=?)";
        $res = $this->db->query($query, [$kelas, $subject, $exam]);
		//		print_r($this->db->last_query()); 
        return $res->result_array();
    }

    public function getAllSoalSelected($exam = NULL) {
        $query = "SELECT * FROM soal_exam a 
                  LEFT JOIN exam b ON a.exam_id=b.exam_id
                  JOIN soal c ON a.soal_id=c.soal_id
                  WHERE (a.exam_id=? OR a.code=?)";
        $res = $this->db->query($query, [$exam, $exam]);
        return $res->result_array();
    }

    private function queryString() {
        $query = "
                  WITH soal AS (
                    SELECT exam_id, COUNT(*) as total_soal FROM soal_exam GROUP BY exam_id
                  )
                  SELECT a.exam_id as id_ujian, a.code as kode_ujian, a.start_date as start_time, a.end_date as end_time, a.duration as durasi,
                        a.subject_id, a.class_level_id as class_id, b.code as subject_code, b.subject_name, c.class_level_name AS class_name,  d.total_soal , f.*
                  FROM exam a
                  JOIN subject b ON a.subject_id=b.subject_id
                  JOIN class_level c ON  b.class_level_id=c.class_level_id 
                  JOIN exam_category f ON a.category_id=f.category_id
                  LEFT JOIN soal d ON a.exam_id=d.exam_id";
        return $query;
    }
}