<?php

class Model_subject extends CI_Model {

	public function __construct(){
        parent::__construct();
    }

	public function get_by_class_level($class_levels = []){
		return $this->db->where_in('class_level_id', $class_levels)
		->get('subject s')->result_array();
	}

}
