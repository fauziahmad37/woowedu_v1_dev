<?php

class Model_class_teacher extends CI_Model {

	public function __construct(){
        parent::__construct();
    }

	public function get($teacher_id){
		return $this->db->where('ct.teacher_id', $teacher_id)
		->join('kelas k', 'k.class_id = ct.class_id', 'left')
		->get('class_teacher ct')->result_array();
	}

}
