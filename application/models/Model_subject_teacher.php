<?php

class Model_subject_teacher extends CI_Model {

	public function __construct(){
        parent::__construct();
    }

	public function get($teacher_id){
		return $this->db->where('teacher_id', $teacher_id)
		->join('subject s', 's.subject_id=st.subject_id')
		->get('subject_teacher st')->result_array();
	}

}
