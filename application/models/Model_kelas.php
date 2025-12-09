<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_kelas extends CI_Model{

  public function __construct()
  {
	parent::__construct();	
  }

	public function get_kelas_teacher(array $filter = [], int $limit = NULL, int $offset = NULL) {
		$teacher_id = $_SESSION['teacher_id'];

		$q = $this->db->where('teacher_id', $teacher_id)->join('kelas k', 'k.class_id=ct.class_id', 'left')
				->get('class_teacher ct')->result_array();
		return $q;
	}	
	public function count_all_kelas_teacher(array $filter = []) {
		$teacher_id = $_SESSION['teacher_id'];
		
		$q = $this->db->where('teacher_id', $teacher_id)->join('kelas k', 'k.class_id=ct.class_id', 'left')
				->get('class_teacher ct')->num_rows();
		return $q;
	}	

	public function get_class_name($class_id) {
		$this->db->select('class_name');
		$this->db->from('kelas');
		$this->db->where('class_id', $class_id);
		$query=$this->db->get();
		if($query->num_rows()==1)
		{
			return $query->row()->class_name;
		}
		return false;
	}
}
