<?php

class Model_exam_student extends CI_Model {

	public function __construct(){ 
        parent::__construct();
    }

	public function get_exam_by_student_id($student_id, $exam_id) {
		$this->db->select('exam_student.*');
		$this->db->from('exam_student');
		$this->db->where('exam_student.student_id', $student_id);
		$this->db->where('exam_student.exam_id', $exam_id);
		$query = $this->db->get();
		return $query->row_array();
	}

}
